<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriverCmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_cms', function (Blueprint $table) {
            $table->id();
            $table->longText('cms')->nullable();
            $table->tinyInteger('type')->nullable()->comment('1=>privacy_policy,2=>terms & condition,3=>aboutUs');
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
        Schema::dropIfExists('driver_cms');
    }
}
