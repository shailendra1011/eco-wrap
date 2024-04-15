<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToSubCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subcategories', function (Blueprint $table) {
            if (!Schema::hasColumn('subcategories','subcategory_name_pt')) {
                $table->string('subcategory_name_pt')->nullable()->after('subcategory_name_es');
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
        Schema::table('subcategories', function (Blueprint $table) {
            if (!Schema::hasColumn('subcategories','subcategory_name_pt')) {
                $table->dropColumn('subcategory_name_pt');
            }
        });
    }
}
