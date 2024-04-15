<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStoreTableColumnDataType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            if (Schema::hasColumn('stores','store_latitude')) {
                $table->decimal('store_latitude', 10, 8)->nullable()->change();
            }

            if (Schema::hasColumn('stores','store_longitude')) {
                $table->decimal('store_longitude', 10, 8)->nullable()->change();
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
        Schema::table('stores', function (Blueprint $table) {
            if (Schema::hasColumn('stores','store_latitude')) {
                $table->decimal('store_latitude', 10, 8);
            }

            if (Schema::hasColumn('stores','store_longitude')) {
                $table->decimal('store_longitude', 10, 8);
            }
        });
    }
}
