<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->unsignedBigInteger('store_id');
            $table->foreign('store_id')->references('id')->on('stores')->cascadeOnDelete();
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->foreign('driver_id')->references('id')->on('drivers')->cascadeOnDelete();
            $table->bigInteger('order_no');
            $table->decimal('total_price',10,2);
            $table->decimal('tax',10,2);
            $table->decimal('shipping_charge',10,2);
            $table->unsignedBigInteger('delivery_address_id');
            $table->foreign('delivery_address_id')->references('id')->on('user_addresses')->cascadeOnDelete();
            $table->boolean('payment_mode')->comment('1=>online,2=>cash on delivery,3=>wallet');
            $table->tinyInteger('order_status')->comment('0=>return order placed,1=>store accept order,2=>driver assingn,3=>out for pickup,4=>recieved,5=>cancelled')->default(0);
            $table->tinyInteger('cancelled_by')->nullable();
            $table->dateTime('cancelled_at')->nullable();
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
        Schema::dropIfExists('return_orders');
    }
}
