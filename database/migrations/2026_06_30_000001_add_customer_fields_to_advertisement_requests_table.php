<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomerFieldsToAdvertisementRequestsTable extends Migration
{
    public function up()
    {
        Schema::table('advertisement_requests', function (Blueprint $table) {
            $table->string('customer_name')->nullable()->after('target_url');
            $table->string('customer_email')->nullable()->after('customer_name');
            $table->string('customer_phone')->nullable()->after('customer_email');
            $table->string('customer_address')->nullable()->after('customer_phone');
            $table->string('duration')->nullable()->after('customer_address');
        });
    }

    public function down()
    {
        Schema::table('advertisement_requests', function (Blueprint $table) {
            $table->dropColumn([
                'customer_name',
                'customer_email',
                'customer_phone',
                'customer_address',
                'duration',
            ]);
        });
    }
}
