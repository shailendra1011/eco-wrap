<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('driver_name')->nullable();
            $table->string('driver_country_code');
            $table->bigInteger('driver_mobile');
            $table->string('driver_email');
            $table->string('driver_password');
            $table->decimal('driver_lattitude',10,7);
            $table->decimal('driver_longitude',10,8);
            $table->string('driver_address')->nullable();
            $table->string('city');
            $table->mediumText('driver_image')->nullable();
            $table->string('vehicle_type')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->mediumText('vehicle_registration')->nullable();
            $table->mediumText('driving_license')->nullable();
            $table->tinyInteger('document_status')->default(0)->comment('0=>not applied,1=> applied, 2=> verified,3=>rejected');
            $table->boolean('driver_status')->default(1)->comment('1=> online, 0=> offline');
            $table->boolean('isSubscribed')->nullable();
            $table->boolean('status')->default(1)->comment('1=> active, 0=> inactive');
            $table->string('driver_language')->comment('EN=> english, ES=> spanish')->nullable();
            $table->string('device_token')->nullable();
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
        Schema::dropIfExists('drivers');
    }
}
