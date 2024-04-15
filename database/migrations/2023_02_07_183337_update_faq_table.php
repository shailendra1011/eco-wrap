<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFaqTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('faqs', function (Blueprint $table) {

            if (!Schema::hasColumn('faqs','language')) {
                $table->string('language')->nullable()->after('id');
            }

            if (!Schema::hasColumn('faqs','question')) {
                $table->text('question')->after('language');
            }

            if (!Schema::hasColumn('faqs','answer')) {
                $table->longText('answer')->after('question');
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
        Schema::table('faqs', function (Blueprint $table) {
            if (Schema::hasColumn('faqs','language')) {
                $table->dropColumn('language');
            }
        });
    }
}
