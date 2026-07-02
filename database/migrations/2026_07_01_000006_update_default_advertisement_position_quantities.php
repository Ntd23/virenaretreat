<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateDefaultAdvertisementPositionQuantities extends Migration
{
    public function up()
    {
        DB::table('advertisement_positions')
            ->where('code', 'large_banner')
            ->update(['max_active_ads' => 3, 'updated_at' => now()]);

        DB::table('advertisement_positions')
            ->whereIn('code', ['right_sidebar', 'left_sidebar'])
            ->update(['max_active_ads' => 1, 'updated_at' => now()]);
    }

    public function down()
    {
        DB::table('advertisement_positions')
            ->whereIn('code', ['large_banner', 'right_sidebar', 'left_sidebar'])
            ->update(['max_active_ads' => 1, 'updated_at' => now()]);
    }
}
