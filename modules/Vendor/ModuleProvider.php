<?php
namespace Modules\Vendor;

use Illuminate\Support\ServiceProvider;
use Modules\ModuleServiceProvider;
use Modules\Vendor\Models\VendorPayout;

class ModuleProvider extends ModuleServiceProvider
{

    public function boot(){
        $this->loadMigrationsFrom(__DIR__ . '/Migrations');
    }
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouterServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
    }

    public static function getAdminMenu()
    {
        $count = VendorPayout::countInitial();
        return [
            'vendor_payout'=>[
                "position"=>70,
                'url'        => route('vendor.admin.payout.index'),
                'title'      => __("Payouts :count",['count'=>$count ? sprintf('<span class="badge badge-warning">%d</span>',$count) : '']),
                'icon'       => 'icon ion-md-card',
                'permission' => 'user_create',
            ],
            'affiliate'=>[
                "position"=>71,
                'url'        => route('vendor.admin.affiliate.index'),
                'title'      => __("Affiliate"),
                'icon'       => 'icon ion-ios-share-alt',
                'permission' => 'user_create',
            ]
        ];
    }


    public static function getTemplateBlocks(){
        return [
            'vendor_register_form'=>"\\Modules\\Vendor\\Blocks\\VendorRegisterForm",
            'vendor_list'=>"\\Modules\\Vendor\\Blocks\\ListVendor",
        ];
    }
    public static function getUserMenu()
    {
        $res = [];
        $res['booking_report']= [
            'url'        => route('vendor.bookingReport'),
            'title'      => __("Booking Report"),
            'icon'       => 'icon ion-ios-pie',
            'position'   => 81,
            'permission' => 'dashboard_vendor_access',
        ];


        $res['enquiry']= [
            'position'   => 82,
            'icon'       => 'icofont-ebook',
            'url'        => route('vendor.enquiry_report'),
            'title'      => __("Enquiry Report"),
            'permission' => 'enquiry_view',
        ];

        $res['affiliate'] = [
            'url'        => route('vendor.affiliate.products'),
            'title'      => __("Affiliate"),
            'icon'       => 'icon ion-ios-share-alt',
            'position'   => 85,
            'permission' => 'dashboard_vendor_access',
        ];

        if(!setting_item('disable_payout'))
        {
            $res['payout']= [
                'url'        => route('vendor.payout.index'),
                'title'      => __("Payouts"),
                'icon'       => 'icon ion-md-card',
                'position'   => 90,
                'permission' => 'dashboard_vendor_access',
            ];
        }
        if(is_enable_vendor_team()){

            $res['team']= [
                'url'        => route('vendor.team.index'),
                'title'      => __("Teams"),
                'icon'       => 'icon ion-ios-contacts',
                'position'   => 100,
                'permission' => 'dashboard_vendor_access',
            ];
        }
        return $res;
    }

    public static function getUserSubMenu()
    {
        $res = [];
        $res['affiliate_products'] = [
            'id'       => 'affiliate_products',
            'parent'   => 'affiliate',
            'title'    => __("Affiliate Products"),
            'url'      => route('vendor.affiliate.products'),
            'position' => 10,
            'permission' => 'dashboard_vendor_access',
        ];
        $res['affiliate_commissions'] = [
            'id'       => 'affiliate_commissions',
            'parent'   => 'affiliate',
            'title'    => __("Commissions"),
            'url'      => route('vendor.affiliate.commissions'),
            'position' => 20,
            'permission' => 'dashboard_vendor_access',
        ];
        return $res;
    }
}
