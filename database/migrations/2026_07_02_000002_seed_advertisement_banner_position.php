<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SeedAdvertisementBannerPosition extends Migration
{
    public function up()
    {
        DB::table('advertisement_positions')->updateOrInsert(
            ['code' => 'advertisement_banner'],
            [
                'name' => 'Banner quảng cáo',
                'platform' => 'website',
                'ad_type' => 'banner',
                'page' => 'Template Builder',
                'placement' => 'advertisement_banner',
                'description' => 'Banner quảng cáo ngang dùng cho block advertisement_banner.',
                'width' => 1200,
                'height' => 180,
                'base_price' => 0,
                'price_type' => 'daily',
                'default_duration' => null,
                'max_active_ads' => 1,
                'sort_order' => 4,
                'is_active' => true,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }

    public function down()
    {
        DB::table('advertisement_positions')
            ->where('code', 'advertisement_banner')
            ->delete();
    }
}
