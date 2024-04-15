<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToHelpAndSupportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('help_and_supports', function (Blueprint $table) {
            if (!Schema::hasColumn('help_and_supports','reply')) {
                $table->text('reply')->nullable();
            }

            if (!Schema::hasColumn('help_and_supports','replied_at')) {
                $table->dateTime('replied_at')->nullable();
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
        Schema::table('help_and_supports', function (Blueprint $table) {
            if (Schema::hasColumn('help_and_supports','reply')) {
                $table->dropColumn('reply');
            }

            if (Schema::hasColumn('help_and_supports','replied_at')) {
                $table->dropColumn('replied_at');
            }
        });
    }
}
