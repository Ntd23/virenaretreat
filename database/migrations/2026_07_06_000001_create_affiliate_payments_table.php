<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('affiliate_payments')) {
            return;
        }

        Schema::create('affiliate_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('commission_id');
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->unsignedBigInteger('referrer_id')->nullable();
            $table->decimal('amount', 15, 2);
            $table->string('payment_code')->unique();
            $table->string('status', 30)->default('waiting_confirm');
            $table->string('sepay_transaction_id')->nullable()->unique();
            $table->string('sepay_reference_code')->nullable();
            $table->text('sepay_transfer_content')->nullable();
            $table->decimal('sepay_transfer_amount', 15, 2)->nullable();
            $table->dateTime('sepay_transaction_date')->nullable();
            $table->string('sepay_bank_name')->nullable();
            $table->string('sepay_account_number')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->foreign('commission_id')
                ->references('id')
                ->on('affiliate_commissions')
                ->onDelete('cascade');

            if (Schema::hasTable('bookings')) {
                $table->foreign('booking_id')
                    ->references('id')
                    ->on('bookings')
                    ->nullOnDelete();
            } elseif (Schema::hasTable('bravo_bookings')) {
                $table->foreign('booking_id')
                    ->references('id')
                    ->on('bravo_bookings')
                    ->nullOnDelete();
            }

            if (Schema::hasTable('users')) {
                $table->foreign('referrer_id')
                    ->references('id')
                    ->on('users')
                    ->nullOnDelete();
            }
        });
    }

    public function down()
    {
        Schema::dropIfExists('affiliate_payments');
    }
};
