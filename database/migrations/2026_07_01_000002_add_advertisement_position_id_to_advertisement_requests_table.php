<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdvertisementPositionIdToAdvertisementRequestsTable extends Migration
{
    public function up()
    {
        Schema::table('advertisement_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('advertisement_requests', 'advertisement_position_id')) {
                $table->unsignedBigInteger('advertisement_position_id')->nullable()->after('user_id');
                $table->foreign('advertisement_position_id')
                    ->references('id')
                    ->on('advertisement_positions')
                    ->nullOnDelete();
            }
        });
    }

    public function down()
    {
        Schema::table('advertisement_requests', function (Blueprint $table) {
            if (Schema::hasColumn('advertisement_requests', 'advertisement_position_id')) {
                $table->dropForeign(['advertisement_position_id']);
                $table->dropColumn('advertisement_position_id');
            }
        });
    }
}
