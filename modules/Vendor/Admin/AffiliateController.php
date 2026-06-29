<?php
namespace Modules\Vendor\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\AdminController;
use App\User;

class AffiliateController extends AdminController
{
    public function __construct()
    {
        $this->setActiveMenu(route('vendor.admin.affiliate.index'));
    }

    public function index(Request $request)
    {
        $this->checkPermission('user_create'); // Quyền quản trị viên chung

        $query = DB::table('affiliate_commissions')
            ->join('users', 'affiliate_commissions.referrer_id', '=', 'users.id')
            ->join('bravo_bookings', 'affiliate_commissions.booking_id', '=', 'bravo_bookings.id')
            ->select(
                'affiliate_commissions.*',
                'users.first_name',
                'users.last_name',
                'users.email',
                'bravo_bookings.total as booking_total',
                'bravo_bookings.status as booking_status'
            );

        if ($status = $request->query('status')) {
            $query->where('affiliate_commissions.status', $status);
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

    public function approveCommission(Request $request, $id)
    {
        $this->checkPermission('user_create');

        $commission = DB::table('affiliate_commissions')->where('id', $id)->first();

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

        // Cập nhật trạng thái
        DB::table('affiliate_commissions')->where('id', $id)->update([
            'status' => 'approved',
            'updated_at' => now()
        ]);

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

        $commission = DB::table('affiliate_commissions')->where('id', $id)->first();

        if (!$commission) {
            return redirect()->back()->with('error', __('Commission record not found'));
        }

        if ($commission->status !== 'approved') {
            return redirect()->back()->with('error', __('Only approved commission can be marked as paid'));
        }

        // Cập nhật trạng thái sang paid
        DB::table('affiliate_commissions')->where('id', $id)->update([
            'status' => 'paid',
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', __('Commission marked as paid successfully'));
    }
}
