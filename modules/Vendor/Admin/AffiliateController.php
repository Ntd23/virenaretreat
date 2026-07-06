<?php
namespace Modules\Vendor\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\AdminController;
use Modules\Vendor\Models\AffiliateCommission;
use Modules\Vendor\Models\AffiliatePayment;

class AffiliateController extends AdminController
{
    public function __construct()
    {
        $this->setActiveMenu(route('vendor.admin.affiliate.index'));
    }

    public function index(Request $request)
    {
        $this->checkPermission('user_create'); // Quyền quản trị viên chung
        $this->ensureApprovedPayments();
        $dateFilter = $this->getDateFilter($request);

        $query = DB::table('affiliate_commissions')
            ->join('users', 'affiliate_commissions.referrer_id', '=', 'users.id')
            ->join('bravo_bookings', 'affiliate_commissions.booking_id', '=', 'bravo_bookings.id')
            ->leftJoin('affiliate_payments', 'affiliate_commissions.id', '=', 'affiliate_payments.commission_id')
            ->select(
                'affiliate_commissions.*',
                'users.first_name',
                'users.last_name',
                'users.email',
                'bravo_bookings.total as booking_total',
                'bravo_bookings.status as booking_status',
                'affiliate_payments.payment_code as affiliate_payment_code',
                'affiliate_payments.status as affiliate_payment_status'
            );

        if ($status = $request->query('status')) {
            if ($status === 'unpaid') {
                $status = 'approved';
            }
            $query->where('affiliate_commissions.status', $status);
        }

        if (!empty($dateFilter['type'])) {
            $query->whereBetween('affiliate_commissions.created_at', [
                date('Y-m-d H:i:s', $dateFilter['from']),
                date('Y-m-d H:i:s', $dateFilter['to'])
            ]);
        }

        if ($search = $request->query('s')) {
            $query->where(function($q) use ($search) {
                $q->where('users.first_name', 'LIKE', '%' . $search . '%')
                  ->orWhere('users.last_name', 'LIKE', '%' . $search . '%')
                  ->orWhere('users.email', 'LIKE', '%' . $search . '%')
                  ->orWhere('affiliate_commissions.booking_id', $search);
            });
        }

        $query->orderBy('affiliate_commissions.id', 'desc');

        $data = [
            'rows'        => $query->paginate(20),
            'date_filter' => $dateFilter,
            'page_title'  => __("Affiliate Commission Management"),
            'breadcrumbs' => [
                [
                    'name'  => __('Affiliate Management'),
                    'class' => 'active'
                ],
            ]
        ];

        return view('Vendor::admin.affiliate.index', $data);
    }

    protected function ensureApprovedPayments()
    {
        if (!Schema::hasTable('affiliate_payments')) {
            return;
        }

        AffiliateCommission::query()
            ->where('status', AffiliateCommission::STATUS_APPROVED)
            ->whereDoesntHave('payment')
            ->orderBy('id')
            ->chunkById(50, function ($commissions) {
                foreach ($commissions as $commission) {
                    $commission->ensurePayment();
                }
            });
    }

    protected function getDateFilter(Request $request)
    {
        $type = $request->query('filter_type');
        $now = time();
        $filter = [
            'type'  => '',
            'day'   => date('Y-m-d', $now),
            'month' => date('Y-m', $now),
            'year'  => date('Y', $now),
            'from'  => null,
            'to'    => null,
            'label' => __('All dates'),
        ];

        if ($type === 'day') {
            $day = $request->query('filter_day') ?: date('Y-m-d', $now);
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $day) or strtotime($day) === false) {
                $day = date('Y-m-d', $now);
            }
            $filter['type'] = 'day';
            $filter['day'] = $day;
            $filter['from'] = strtotime($day . ' 00:00:00');
            $filter['to'] = strtotime($day . ' 23:59:59');
            $filter['label'] = __('Day: :date', ['date' => display_date($filter['from'])]);
        }

        if ($type === 'month') {
            $month = $request->query('filter_month') ?: date('Y-m', $now);
            if (!preg_match('/^\d{4}-\d{2}$/', $month) or strtotime($month . '-01') === false) {
                $month = date('Y-m', $now);
            }
            $filter['type'] = 'month';
            $filter['month'] = $month;
            $filter['from'] = strtotime($month . '-01 00:00:00');
            $filter['to'] = strtotime(date('Y-m-t 23:59:59', $filter['from']));
            $filter['label'] = __('Month: :date', ['date' => date('m/Y', $filter['from'])]);
        }

        if ($type === 'year') {
            $year = $request->query('filter_year') ?: date('Y', $now);
            if (!preg_match('/^\d{4}$/', $year)) {
                $year = date('Y', $now);
            }
            $filter['type'] = 'year';
            $filter['year'] = $year;
            $filter['from'] = strtotime($year . '-01-01 00:00:00');
            $filter['to'] = strtotime($year . '-12-31 23:59:59');
            $filter['label'] = __('Year: :date', ['date' => $year]);
        }

        return $filter;
    }

    public function approveCommission(Request $request, $id)
    {
        $this->checkPermission('user_create');

        $commission = AffiliateCommission::query()->with('payment')->find($id);

        if (!$commission) {
            return redirect()->back()->with('error', __('Commission record not found'));
        }

        if ($commission->status !== 'pending') {
            return redirect()->back()->with('error', __('Only pending commission can be approved'));
        }

        $booking = DB::table('bravo_bookings')->where('id', $commission->booking_id)->first();
        if (!$booking || $booking->status !== 'completed') {
            return redirect()->back()->with('error', __('Only commission with completed booking can be approved'));
        }

        DB::transaction(function () use ($commission) {
            $commission = AffiliateCommission::query()
                ->where('id', $commission->id)
                ->lockForUpdate()
                ->first();

            $updates = [
                'status' => AffiliateCommission::STATUS_APPROVED,
                'updated_at' => now(),
            ];

            if (Schema::hasColumn('affiliate_commissions', 'approved_at')) {
                $updates['approved_at'] = now();
            }
            if (Schema::hasColumn('affiliate_commissions', 'approved_by')) {
                $updates['approved_by'] = Auth::id();
            }

            $commission->update($updates);
            $commission->load('payment');
            $commission->ensurePayment();
        });

        return redirect()->back()->with('success', __('Commission approved successfully'));
    }

    public function rejectCommission(Request $request, $id)
    {
        $this->checkPermission('user_create');

        $commission = DB::table('affiliate_commissions')->where('id', $id)->first();

        if (!$commission) {
            return redirect()->back()->with('error', __('Commission record not found'));
        }

        if ($commission->status !== 'pending') {
            return redirect()->back()->with('error', __('Only pending commission can be rejected'));
        }

        // Cập nhật trạng thái
        DB::table('affiliate_commissions')->where('id', $id)->update([
            'status' => 'cancelled',
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', __('Commission rejected successfully'));
    }

    public function payCommission(Request $request, $id)
    {
        $this->checkPermission('user_create');

        $commission = AffiliateCommission::query()->with('payment')->find($id);

        if (!$commission) {
            return redirect()->back()->with('error', __('Commission record not found'));
        }

        if ($commission->status !== 'approved') {
            return redirect()->back()->with('error', __('Only approved commission can be marked as paid'));
        }

        DB::transaction(function () use ($commission) {
            $commission = AffiliateCommission::query()
                ->where('id', $commission->id)
                ->lockForUpdate()
                ->first();

            $paidAt = now();
            $updates = [
                'status' => AffiliateCommission::STATUS_PAID,
                'updated_at' => $paidAt,
            ];

            if (Schema::hasColumn('affiliate_commissions', 'paid_at')) {
                $updates['paid_at'] = $paidAt;
            }

            $commission->update($updates);
            $commission->load('payment');
            $payment = $commission->ensurePayment();

            if ($payment && $payment->status !== AffiliatePayment::STATUS_PAID) {
                $payment->update([
                    'status' => AffiliatePayment::STATUS_PAID,
                    'paid_at' => $paidAt,
                ]);
            }
        });

        return redirect()->back()->with('success', __('Commission marked as paid successfully'));
    }
}
