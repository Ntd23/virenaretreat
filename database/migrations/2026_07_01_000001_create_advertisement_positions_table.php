<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertisementPositionsTable extends Migration
{
    public function up()
    {
        Schema::create('advertisement_positions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('code')->unique();
            $table->string('platform')->default('website');
            $table->string('ad_type')->nullable();
            $table->string('page')->nullable();
            $table->string('placement')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->decimal('base_price', 15, 2)->default(0);
            $table->string('price_type')->default('fixed');
            $table->unsignedInteger('default_duration')->nullable();
            $table->unsignedInteger('max_active_ads')->default(1);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['platform', 'is_active']);
            $table->index('sort_order');
        });
    }

    public function down()
    {
        Schema::dropIfExists('advertisement_positions');
    }
}
