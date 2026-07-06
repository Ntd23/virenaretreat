<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('affiliate_commissions')) {
            return;
        }

        Schema::table('affiliate_commissions', function (Blueprint $table) {
            if (!Schema::hasColumn('affiliate_commissions', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('status');
            }
            if (!Schema::hasColumn('affiliate_commissions', 'approved_by')) {
                $table->unsignedBigInteger('approved_by')->nullable()->after('approved_at');
            }
            if (!Schema::hasColumn('affiliate_commissions', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('approved_by');
            }
        });

        if (Schema::hasTable('users') && Schema::hasColumn('affiliate_commissions', 'approved_by')) {
            Schema::table('affiliate_commissions', function (Blueprint $table) {
                try {
                    $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
                } catch (\Throwable $e) {
                    // Some MySQL versions throw if the foreign key already exists.
                }
            });
        }
    }

    public function down()
    {
        if (!Schema::hasTable('affiliate_commissions')) {
            return;
        }

        Schema::table('affiliate_commissions', function (Blueprint $table) {
            if (Schema::hasColumn('affiliate_commissions', 'approved_by')) {
                try {
                    $table->dropForeign(['approved_by']);
                } catch (\Throwable $e) {
                    //
                }
            }
            foreach (['paid_at', 'approved_by', 'approved_at'] as $column) {
                if (Schema::hasColumn('affiliate_commissions', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
