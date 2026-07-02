<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SeedDefaultAdvertisementPositions extends Migration
{
    public function up()
    {
        $positions = [
            [
                'name' => 'Banner lớn',
                'code' => 'large_banner',
                'placement' => 'large_banner',
                'sort_order' => 1,
            ],
            [
                'name' => 'Sidebar phải',
                'code' => 'right_sidebar',
                'placement' => 'right_sidebar',
                'sort_order' => 2,
            ],
            [
                'name' => 'Sidebar trái',
                'code' => 'left_sidebar',
                'placement' => 'left_sidebar',
                'sort_order' => 3,
            ],
        ];

        foreach ($positions as $position) {
            DB::table('advertisement_positions')->updateOrInsert(
                ['code' => $position['code']],
                array_merge($position, [
                    'platform' => 'website',
                    'price_type' => 'fixed',
                    'max_active_ads' => 1,
                    'is_active' => true,
                    'updated_at' => now(),
                    'created_at' => now(),
                ])
            );
        }
    }

    public function down()
    {
        DB::table('advertisement_positions')
            ->whereIn('code', ['large_banner', 'right_sidebar', 'left_sidebar'])
            ->delete();
    }
}
