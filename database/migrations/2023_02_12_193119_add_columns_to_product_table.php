<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {

            if (Schema::hasColumn('products','product_language')) {
                $table->string('product_language',20)->change();
            }

            if (!Schema::hasColumn('products','product_name_pt')) {
                $table->string('product_name_pt')->nullable();
            }

            if (!Schema::hasColumn('products','description_pt')) {
                $table->longText('description_pt')->nullable();
            }

            if (!Schema::hasColumn('products','manufacturer_name_pt')) {
                $table->string('manufacturer_name_pt')->nullable();
            }

            if (!Schema::hasColumn('products','direction_to_use_pt')) {
                $table->longText('direction_to_use_pt')->nullable();
            }

            if (!Schema::hasColumn('products','ingredients_pt')) {
                $table->longText('ingredients_pt')->nullable();
            }

            if (!Schema::hasColumn('products','other_info_pt')) {   
                $table->longText('other_info_pt')->nullable();
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
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products','product_name_pt')) {
                $table->dropColumn('product_name_pt');
            }

            if (Schema::hasColumn('products','description_pt')) {
                $table->dropColumn('description_pt');
            }

            if (Schema::hasColumn('products','manufacturer_name_pt')) {
                $table->dropColumn('manufacturer_name_pt');
            }

            if (Schema::hasColumn('products','direction_to_use_pt')) {
                $table->dropColumn('direction_to_use_pt');
            }

            if (Schema::hasColumn('products','ingredients_pt')) {
                $table->dropColumn('ingredients_pt');
            }

            if (Schema::hasColumn('products','other_info_pt')) {   
                $table->dropColumn('other_info_pt');
            }
        });
    }
}
