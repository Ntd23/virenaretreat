<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdminAdvertisementFields extends Migration
{
    public function up()
    {
        Schema::table('advertisement_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('advertisement_requests', 'platform')) {
                $table->string('platform')->nullable()->after('title')->index();
            }
            if (!Schema::hasColumn('advertisement_requests', 'ad_type')) {
                $table->string('ad_type')->nullable()->after('platform');
            }
            if (!Schema::hasColumn('advertisement_requests', 'content')) {
                $table->longText('content')->nullable()->after('description');
            }
            if (!Schema::hasColumn('advertisement_requests', 'media_urls')) {
                $table->json('media_urls')->nullable()->after('content');
            }
            if (!Schema::hasColumn('advertisement_requests', 'link_url')) {
                $table->string('link_url')->nullable()->after('target_url');
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
            if (!Schema::hasColumn('advertisement_requests', 'note')) {
                $table->text('note')->nullable()->after('target_gender');
            }
            if (!Schema::hasColumn('advertisement_requests', 'original_price')) {
                $table->decimal('original_price', 15, 2)->nullable()->after('rejection_reason');
            }
            if (!Schema::hasColumn('advertisement_requests', 'discount_amount')) {
                $table->decimal('discount_amount', 15, 2)->default(0)->after('original_price');
            }
            if (!Schema::hasColumn('advertisement_requests', 'final_price')) {
                $table->decimal('final_price', 15, 2)->nullable()->after('discount_amount');
            }
            if (!Schema::hasColumn('advertisement_requests', 'reject_reason')) {
                $table->text('reject_reason')->nullable()->after('final_price');
            }
            if (!Schema::hasColumn('advertisement_requests', 'confirmed_by')) {
                $table->unsignedBigInteger('confirmed_by')->nullable()->after('approved_at');
            }
            if (!Schema::hasColumn('advertisement_requests', 'confirmed_at')) {
                $table->dateTime('confirmed_at')->nullable()->after('confirmed_by');
            }
        });

        Schema::table('advertisement_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('advertisement_payments', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('advertisement_request_id')->index();
            }
            if (!Schema::hasColumn('advertisement_payments', 'payment_method')) {
                $table->string('payment_method', 50)->default('')->after('paid_amount');
            }
            if (!Schema::hasColumn('advertisement_payments', 'sepay_gateway')) {
                $table->string('sepay_gateway')->nullable()->after('sepay_transaction_id');
            }
            if (!Schema::hasColumn('advertisement_payments', 'sepay_transfer_content')) {
                $table->text('sepay_transfer_content')->nullable()->after('sepay_content');
            }
            if (!Schema::hasColumn('advertisement_payments', 'sepay_transaction_date')) {
                $table->dateTime('sepay_transaction_date')->nullable()->after('sepay_transfer_content');
            }
        });
    }

    public function down()
    {
        Schema::table('advertisement_payments', function (Blueprint $table) {
            foreach (['sepay_transaction_date', 'sepay_transfer_content', 'sepay_gateway', 'payment_method', 'user_id'] as $column) {
                if (Schema::hasColumn('advertisement_payments', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('advertisement_requests', function (Blueprint $table) {
            foreach ([
                'confirmed_at',
                'confirmed_by',
                'reject_reason',
                'final_price',
                'discount_amount',
                'original_price',
                'note',
                'target_gender',
                'target_age_max',
                'target_age_min',
                'target_locations',
                'end_at',
                'start_at',
                'link_url',
                'media_urls',
                'content',
                'ad_type',
                'platform',
            ] as $column) {
                if (Schema::hasColumn('advertisement_requests', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}
