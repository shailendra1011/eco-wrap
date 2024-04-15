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
            $table->string('store_name_es')->nullable();
            $table->string('store_country_code');
            $table->bigInteger('store_mobile');
            $table->string('email');
            $table->decimal('store_latitude', 10, 8);
            $table->decimal('store_longitude', 10, 8);
            $table->string('store_address')->nullable();
            $table->string('store_city')->nullable();
            $table->string('store_url')->nullable();
            $table->string('stripe_connect_id')->nullable();
            $table->boolean('completed_stripe_onboarding')->default(false);
            $table->unsignedBigInteger('category_id');
            $table->mediumText('about_us')->nullable();
            $table->mediumText('about_us_es')->nullable();
            $table->string('password');
            $table->tinyInteger('store_language')->comment('1=>English,2=>Spanish,3=>both')->default(1);
            $table->boolean('store_status')->default(1)->comment('1=>online,0=>offline');
            $table->boolean('status')->default(0)->comment('admin status 0=>inactive,1=>active');
            $table->tinyInteger('food_service_type')->nullable()->comment('1=>online order only,2=>dining only,3=>both');
            $table->decimal('rating', 2, 1)->default(0);
            $table->string('device_token')->nullable();
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
