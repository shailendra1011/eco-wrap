<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id');
            $table->string('product_name')->nullable();
            $table->string('product_title')->nullable();
            $table->string('product_category')->nullable();
            $table->string('product_type')->nullable();
            $table->string('product_size')->nullable();
            $table->string('material')->nullable();
            $table->string('product_colour')->nullable();
            $table->string('price')->nullable();
            $table->string('special_price')->nullable();
            $table->string('price_per_piece')->nullable();
            $table->string('minimum_quantity')->nullable();
            $table->string('maximum_quantity')->nullable();
            $table->string('status')->nullable();
            $table->string('Specifications')->nullable();
            $table->string('product_description')->nullable();
            $table->string('product_short_description')->nullable();
            $table->string('delivery_time')->nullable();
            $table->string('product_weight')->nullable();
            $table->string('print_on_demand')->nullable();
            $table->string('design_type')->nullable();
            $table->string('printing_service')->nullable();
            $table->string('service_name')->nullable();
            $table->string('example')->nullable();
            $table->string('on_demand_design')->nullable();
            $table->string('contact')->nullable();
            $table->string('address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
