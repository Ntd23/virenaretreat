<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDurationDiscountsToAdvertisementPositionsTable extends Migration
{
    public function up()
    {
        Schema::table('advertisement_positions', function (Blueprint $table) {
            if (!Schema::hasColumn('advertisement_positions', 'duration_discounts')) {
                $table->json('duration_discounts')->nullable()->after('monthly_discount_percent');
            }
        });
    }

    public function down()
    {
        Schema::table('advertisement_positions', function (Blueprint $table) {
            if (Schema::hasColumn('advertisement_positions', 'duration_discounts')) {
                $table->dropColumn('duration_discounts');
            }
        });
    }
}
