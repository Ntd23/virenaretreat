<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertisementPaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('advertisement_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('advertisement_request_id');
            $table->string('payment_code', 100)->unique();
            $table->string('qr_url')->nullable();
            $table->decimal('amount', 15, 2);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->string('payment_status', 50)->default('pending')->index();
            $table->string('sepay_transaction_id', 100)->nullable()->unique();
            $table->string('sepay_code')->nullable();
            $table->text('sepay_content')->nullable();
            $table->json('sepay_payload')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->timestamps();

            $table->foreign('advertisement_request_id', 'ad_payments_request_fk')
                ->references('id')->on('advertisement_requests')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('advertisement_payments');
    }
}
