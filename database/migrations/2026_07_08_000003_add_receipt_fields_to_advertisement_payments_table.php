<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('advertisement_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('advertisement_payments', 'receipt_image_url')) {
                $table->string('receipt_image_url')->nullable()->after('qr_url');
            }

            if (!Schema::hasColumn('advertisement_payments', 'receipt_uploaded_at')) {
                $table->timestamp('receipt_uploaded_at')->nullable()->after('receipt_image_url');
            }
        });
    }

    public function down()
    {
        Schema::table('advertisement_payments', function (Blueprint $table) {
            if (Schema::hasColumn('advertisement_payments', 'receipt_uploaded_at')) {
                $table->dropColumn('receipt_uploaded_at');
            }

            if (Schema::hasColumn('advertisement_payments', 'receipt_image_url')) {
                $table->dropColumn('receipt_image_url');
            }
        });
    }
};
