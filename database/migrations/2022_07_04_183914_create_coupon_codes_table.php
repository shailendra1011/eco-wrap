<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_codes', function (Blueprint $table) {
            $table->id();
            $table->enum('coupon_type',['individual','global'])->comment("Individual => Vendor's coupon, Global => Admin's coupon");
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete()->default('NULL')->comment('NULL in case of admin');
            $table->string('coupon_name',100)->nullable();
            $table->string('coupon_code',50);
            $table->enum('discount_type',['flat','percentage']);
            $table->unsignedMediumInteger('min_cart_value')->default(0);
            $table->unsignedSmallInteger('discount_value');
            $table->unsignedMediumInteger('maximum_discount')->nullable()->comment('Applicable in case of percentage discount type.');
            $table->date('start_date');
            $table->date('end_date');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(1);
            $table->softDeletes();
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
        Schema::dropIfExists('coupon_codes');
    }
}
