<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriverSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subscription_plan_id');
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->string('transction_id')->nullable();
            $table->timestamp('expired_at');
            $table->tinyInteger('status')->nullable()->default(1)->comment('1=>subscribed,0=>not subscribed');
            $table->foreign('subscription_plan_id')->references('id')->on('subscription_plans')->cascadeOnDelete();
            $table->foreign('driver_id')->references('id')->on('drivers')->cascadeOnDelete();
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
        Schema::dropIfExists('driver_subscriptions');
    }
}
