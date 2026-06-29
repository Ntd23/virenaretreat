<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affiliate_clicks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('referrer_id')->unsigned();
            $table->bigInteger('object_id')->unsigned();
            $table->string('object_model', 50);
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->foreign('referrer_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('affiliate_commissions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('booking_id')->unsigned()->unique();
            $table->bigInteger('referrer_id')->unsigned();
            $table->string('commission_type', 20);
            $table->decimal('commission_rate', 10, 2);
            $table->decimal('commission_amount', 10, 2);
            $table->string('status', 20)->default('pending');
            $table->timestamps();

            $table->foreign('booking_id')->references('id')->on('bravo_bookings')->onDelete('cascade');
            $table->foreign('referrer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('affiliate_commissions');
        Schema::dropIfExists('affiliate_clicks');
    }
};
