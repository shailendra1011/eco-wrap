<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('store_name')->nullable();
            $table->bigInteger('store_mobile');
            $table->string('email');
            $table->string('address');

            $table->string('gst')->nullable();;
            $table->string('adhar_image')->nullable();
            $table->string('payment_receiving_mode')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('account_no')->nullable();
            $table->string('cancelled_cheque_image')->nullable();
            $table->string('upi_id')->nullable();

            $table->string('store_address')->nullable();
            $table->string('store_url')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->string('password');
            $table->boolean('store_status')->default(1)->comment('1=>online,0=>offline');
            $table->boolean('status')->default(0)->comment('admin status 0=>inactive,1=>active');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('categories')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stores');
    }
}
