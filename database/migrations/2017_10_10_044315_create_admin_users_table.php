<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',100);
            $table->string('email',100)->unique();
            $table->string('password',100);
            $table->string('remember_token',100)->nullable();
            $table->timestamps();
            $table->tinyInteger('status')->default(1)->nullable()->comment('1 - Active , 2 - Inactive, 3 - Deleted');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_users');
    }
}
