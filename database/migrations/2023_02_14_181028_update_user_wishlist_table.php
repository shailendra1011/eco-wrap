<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUserWishlistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_wishlists', function (Blueprint $table) {

            if (Schema::hasColumn('user_wishlists','store_id')) {
                $table->dropForeign(['store_id']);
                $table->dropColumn('store_id');
            }

            if (!Schema::hasColumn('user_wishlists','product_id')) {
                $table->unsignedBigInteger('product_id')->after('user_id');
            }

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_wishlists', function (Blueprint $table) {
            $table->dropColumn('product_id');
        });
    }
}
