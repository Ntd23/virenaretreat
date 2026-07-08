<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMultiPositionAndSelectedMediaToAdvertisementRequestsTable extends Migration
{
    public function up()
    {
        Schema::table('advertisement_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('advertisement_requests', 'advertisement_position_ids')) {
                $table->json('advertisement_position_ids')->nullable()->after('advertisement_position_id');
            }

            if (!Schema::hasColumn('advertisement_requests', 'selected_media_url')) {
                $table->text('selected_media_url')->nullable()->after('media_urls');
            }
        });
    }

    public function down()
    {
        Schema::table('advertisement_requests', function (Blueprint $table) {
            if (Schema::hasColumn('advertisement_requests', 'selected_media_url')) {
                $table->dropColumn('selected_media_url');
            }

            if (Schema::hasColumn('advertisement_requests', 'advertisement_position_ids')) {
                $table->dropColumn('advertisement_position_ids');
            }
        });
    }
}
