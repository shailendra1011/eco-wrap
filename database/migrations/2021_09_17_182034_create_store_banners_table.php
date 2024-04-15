<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_banners', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id');
            $table->mediumText('store_banner');            
            $table->timestamps();
            $table->foreign('store_id')->references('id')->on('stores')->cascadeOnDelete();
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_banners');
    }
}
