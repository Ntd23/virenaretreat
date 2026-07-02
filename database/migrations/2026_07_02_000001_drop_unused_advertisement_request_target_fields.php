<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropUnusedAdvertisementRequestTargetFields extends Migration
{
    public function up()
    {
        Schema::table('advertisement_requests', function (Blueprint $table) {
            foreach ([
                'target_gender',
                'target_age_max',
                'target_age_min',
                'target_locations',
                'end_at',
                'start_at',
                'ad_type',
                'platform',
            ] as $column) {
                if (Schema::hasColumn('advertisement_requests', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    public function down()
    {
        Schema::table('advertisement_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('advertisement_requests', 'platform')) {
                $table->string('platform')->nullable()->after('title')->index();
            }

            if (!Schema::hasColumn('advertisement_requests', 'ad_type')) {
                $table->string('ad_type')->nullable()->after('platform');
            }

            if (!Schema::hasColumn('advertisement_requests', 'start_at')) {
                $table->dateTime('start_at')->nullable()->after('start_date');
            }

            if (!Schema::hasColumn('advertisement_requests', 'end_at')) {
                $table->dateTime('end_at')->nullable()->after('start_at');
            }

            if (!Schema::hasColumn('advertisement_requests', 'target_locations')) {
                $table->json('target_locations')->nullable()->after('end_at');
            }

            if (!Schema::hasColumn('advertisement_requests', 'target_age_min')) {
                $table->unsignedTinyInteger('target_age_min')->nullable()->after('target_locations');
            }

            if (!Schema::hasColumn('advertisement_requests', 'target_age_max')) {
                $table->unsignedTinyInteger('target_age_max')->nullable()->after('target_age_min');
            }

            if (!Schema::hasColumn('advertisement_requests', 'target_gender')) {
                $table->string('target_gender', 30)->nullable()->after('target_age_max');
            }
        });
    }
}
