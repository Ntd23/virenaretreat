<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('advertisement_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('advertisement_requests', 'selected_media_urls')) {
                $table->json('selected_media_urls')->nullable()->after('selected_media_url');
            }
        });
    }

    public function down()
    {
        Schema::table('advertisement_requests', function (Blueprint $table) {
            if (Schema::hasColumn('advertisement_requests', 'selected_media_urls')) {
                $table->dropColumn('selected_media_urls');
            }
        });
    }
};
