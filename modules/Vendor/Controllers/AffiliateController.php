<?php
namespace Modules\Vendor\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\FrontendController;

class AffiliateController extends FrontendController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        // Kích hoạt menu sidebar sáng lên
        $this->setActiveMenu(route('vendor.affiliate.products'));
    }

    /**
     * Danh sách sản phẩm được phép làm Affiliate
     */
    public function products(Request $request)
    {
        $this->checkPermission('dashboard_vendor_access');

        $search = $request->query('s');

        // Lấy tất cả các tour đang bật affiliate
        $tours = DB::table('bravo_tours')
            ->where('is_affiliate', 1)
            ->where('status', 'publish')
            ->select('id', 'title', 'slug', 'price', 'is_affiliate', 'affiliate_commission_type', 'affiliate_commission_value', DB::raw('"tour" as object_model'));

        // Lấy tất cả các hotel đang bật affiliate
        $hotels = DB::table('bravo_hotels')
            ->where('is_affiliate', 1)
            ->where('status', 'publish')
            ->select('id', 'title', 'slug', 'price', 'is_affiliate', 'affiliate_commission_type', 'affiliate_commission_value', DB::raw('"hotel" as object_model'));

        // Lấy tất cả các space đang bật affiliate
        $spaces = DB::table('bravo_spaces')
            ->where('is_affiliate', 1)
            ->where('status', 'publish')
            ->select('id', 'title', 'slug', 'price', 'is_affiliate', 'affiliate_commission_type', 'affiliate_commission_value', DB::raw('"space" as object_model'));

        // Lấy tất cả các car đang bật affiliate
        $cars = DB::table('bravo_cars')
            ->where('is_affiliate', 1)
            ->where('status', 'publish')
            ->select('id', 'title', 'slug', 'price', 'is_affiliate', 'affiliate_commission_type', 'affiliate_commission_value', DB::raw('"car" as object_model'));

        // Lấy tất cả các boat đang bật affiliate
        $boats = DB::table('bravo_boats')
            ->where('is_affiliate', 1)
            ->where('status', 'publish')
            ->select('id', 'title', 'slug', 'min_price as price', 'is_affiliate', 'affiliate_commission_type', 'affiliate_commission_value', DB::raw('"boat" as object_model'));

        if ($search) {
            $tours->where('title', 'LIKE', '%' . $search . '%');
            $hotels->where('title', 'LIKE', '%' . $search . '%');
            $spaces->where('title', 'LIKE', '%' . $search . '%');
            $cars->where('title', 'LIKE', '%' . $search . '%');
            $boats->where('title', 'LIKE', '%' . $search . '%');
        }

        // Gộp kết quả
        $query = $tours->union($hotels)->union($spaces)->union($cars)->union($boats);

        // Phân trang
        $products = $query->paginate(15);

        // Bổ sung link chi tiết cho từng sản phẩm
        foreach ($products->items() as $item) {
            $class = null;
            switch ($item->object_model) {
                case 'tour':
                    $class = \Modules\Tour\Models\Tour::class;
                    break;
                case 'hotel':
                    $class = \Modules\Hotel\Models\Hotel::class;
                    break;
                case 'space':
                    $class = \Modules\Space\Models\Space::class;
                    break;
                case 'car':
                    $class = \Modules\Car\Models\Car::class;
                    break;
                case 'boat':
                    $class = \Modules\Boat\Models\Boat::class;
                    break;
            }
            if ($class) {
                $service = new $class();
                $service->id = $item->id;
                $service->slug = $item->slug;
                $item->detail_url = $service->getDetailUrl();
            } else {
                $item->detail_url = url('/');
            }
        }

        $data = [
            'rows'        => $products,
            'page_title'  => __("Affiliate Products"),
            'breadcrumbs' => [
                [
                    'name' => __('Vendor dashboard'),
                    'url'  => route('vendor.dashboard')
                ],
                [
                    'name'  => __('Affiliate Products'),
                    'class' => 'active'
                ],
            ]
        ];

        return view('Vendor::frontend.affiliate.products', $data);
    }

    /**
     * Báo cáo click & Hoa hồng nhận được
     */
    public function commissions(Request $request)
    {
        $this->checkPermission('dashboard_vendor_access');
        $userId = Auth::id();

        // Thống kê nhanh
        $totalClicks = DB::table('affiliate_clicks')
            ->where('referrer_id', $userId)
            ->count();

        $totalApprovedAmount = DB::table('affiliate_commissions')
            ->where('referrer_id', $userId)
            ->where('status', 'approved')
            ->sum('commission_amount');

        $totalPendingAmount = DB::table('affiliate_commissions')
            ->where('referrer_id', $userId)
            ->where('status', 'pending')
            ->sum('commission_amount');

        $totalPaidAmount = DB::table('affiliate_commissions')
            ->where('referrer_id', $userId)
            ->where('status', 'paid')
            ->sum('commission_amount');

        // Danh sách hoa hồng
        $commissions = DB::table('affiliate_commissions')
            ->where('referrer_id', $userId)
            ->orderBy('id', 'desc')
            ->paginate(15);

        $data = [
            'rows'                 => $commissions,
            'total_clicks'         => $totalClicks,
            'total_approved_amount'=> $totalApprovedAmount,
            'total_pending_amount' => $totalPendingAmount,
            'total_paid_amount'    => $totalPaidAmount,
            'page_title'           => __("Commissions"),
            'breadcrumbs'          => [
                [
                    'name' => __('Vendor dashboard'),
                    'url'  => route('vendor.dashboard')
                ],
                [
                    'name'  => __('Commissions'),
                    'class' => 'active'
                ],
            ]
        ];

        return view('Vendor::frontend.affiliate.commissions', $data);
    }

    public function savePayoutAccount(Request $request)
    {
        $this->checkPermission('dashboard_vendor_access');
        $user = \Illuminate\Support\Facades\Auth::user();

        $account = $request->input('affiliate_payout_account', []);
        
        $user->addMeta('affiliate_payout_account', json_encode($account));

        return redirect()->back()->with('success', __('Affiliate payout account saved successfully'));
    }
}
