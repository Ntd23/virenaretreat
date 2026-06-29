<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tables = ['bravo_tours', 'bravo_hotels', 'bravo_spaces', 'bravo_cars', 'bravo_boats'];
        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->tinyInteger('is_affiliate')->default(0);
                    $table->string('affiliate_commission_type', 20)->default('percent');
                    $table->decimal('affiliate_commission_value', 10, 2)->default(0);
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tables = ['bravo_tours', 'bravo_hotels', 'bravo_spaces', 'bravo_cars', 'bravo_boats'];
        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn(['is_affiliate', 'affiliate_commission_type', 'affiliate_commission_value']);
                });
            }
        }
    }
};
