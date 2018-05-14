<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableOffers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('login_id')->default(0);
            $table->integer('asset_id')->default(0);
            $table->string('payment_method',50)->nullable();
            $table->string('payment_method_mortgage',50)->nullable()->comment('Approved / Not Approved');
            $table->string('payment_method_mortgage_dp_amount',50)->nullable();
            $table->string('payment_method_mortgage_certificate',100)->nullable();
            $table->tinyInteger('step_1_completed')->default(0)->comment('0 - No, 1 - Yes');
            $table->string('owner_offer_price',50)->nullable();
            $table->string('customer_offer_price',50)->nullable();
            $table->tinyInteger('step_2_completed')->default(0)->comment('0 - No, 1 - Yes');
            $table->string('customer_name',50)->nullable();
            $table->string('customer_phone',20)->nullable();
            $table->string('customer_email',100)->nullable();
            $table->tinyInteger('step_3_completed')->default(0)->comment('0 - No, 1 - Yes');
            $table->tinyInteger('mark_as_visited')->default(0)->comment('0 - No, 1 - Yes');
            $table->tinyInteger('step_4_completed')->default(0)->comment('0 - No, 1 - Yes');
            $table->tinyInteger('step_5_completed')->default(0)->comment('0 - No, 1 - Yes');
            $table->tinyInteger('step_6_completed')->default(0)->comment('0 - No, 1 - Yes');
            $table->string('signature_schedule_1',20)->nullable();
            $table->string('signature_schedule_2',20)->nullable();
            $table->string('signature_schedule_3',20)->nullable();
            $table->tinyInteger('step_7_completed')->default(0)->comment('0 - No, 1 - Yes');
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
        Schema::dropIfExists('offers');
    }
}
