<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStoreBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_banners', function (Blueprint $table) {
            if (!Schema::hasColumn('store_banners','meta_title')) {
                $table->string('meta_title')->nullable()->after('store_banner');
            }

            if (!Schema::hasColumn('store_banners','meta_description')) {
                $table->string('meta_description')->nullable()->after('meta_title');
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
        Schema::table('store_banners', function (Blueprint $table) {
            if (Schema::hasColumn('store_banners','meta_title')) {
                $table->dropColumn('meta_title');
            }

            if (Schema::hasColumn('store_banners','meta_description')) {
                $table->dropColumn('meta_description');
            }
        });
    }
}
