<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('user_id')->unsignedInteger();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->integer('age');
            $table->enum('gender',['male','female','both'])->defualt('male');
            $table->integer('age_min');
            $table->integer('age_max');
            $table->float('distance');
            $table->enum('complexion',['dark','fair'])->default('dark');
            $table->integer('height');
            $table->enum('status',['active','disabled', 'blocked']);
            $table->datetime('interest_set_at');
            $table->datetime('preference_set_at');
            $table->datetime('accepted_terms_at');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
