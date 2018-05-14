<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableOfferOfferBuyersInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offer_buyers_info', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('offer_id')->default(0);
            $table->string('first_name',100)->nullable();
            $table->string('last_name',100)->nullable();
            $table->string('photo',100)->nullable();
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
        Schema::dropIfExists('offer_buyers_info');
    }
}
