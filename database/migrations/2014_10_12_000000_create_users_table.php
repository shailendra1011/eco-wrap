<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('country_code');
            $table->bigInteger('mobile'); 
            $table->decimal('user_lattitude',10,8);
            $table->decimal('user_longitude',10,8);
            $table->string('user_address')->nullable();
            $table->string('city');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->mediumText('user_image')->nullable();
            $table->string('device_token')->default(1)->nullable();
            $table->string('referral_code');
            $table->unsignedBigInteger('referred_by_user_id')->nullable();
            $table->unsignedInteger('referral_points')->default(0);
            $table->boolean('user_status')->default(1)->comment('1=> active, 0=> inactive');
            $table->string('user_language')->comment('EN=> english, ES=> spanish')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
