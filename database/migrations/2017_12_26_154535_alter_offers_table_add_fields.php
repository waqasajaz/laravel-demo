<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOffersTableAddFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offers', function($table) {
            $table->string('schedule_visit_1', 25)->nullable()->after('step_3_completed');
            $table->string('schedule_visit_2', 25)->nullable()->after('schedule_visit_1');
            $table->string('first_name_1', 50)->nullable()->after('step_5_completed');
            $table->string('last_name_1', 50)->nullable()->after('first_name_1');
            $table->string('photo_1', 100)->nullable()->after('last_name_1');
            $table->string('first_name_2', 50)->nullable()->after('photo_1');
            $table->string('last_name_2', 50)->nullable()->after('first_name_2');
            $table->string('photo_2', 100)->nullable()->after('last_name_2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offers', function($table) {
            $table->dropColumn('schedule_visit_1');
            $table->dropColumn('schedule_visit_2');
            $table->dropColumn('first_name_1');
            $table->dropColumn('last_name_1');
            $table->dropColumn('photo_1');
            $table->dropColumn('first_name_2');
            $table->dropColumn('last_name_2');
            $table->dropColumn('photo_2');
        });
    }
}