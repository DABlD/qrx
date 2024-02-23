<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->increments('id');
            $table->string('username')->unique();
            
            $table->string('fname')->nullable();
            $table->string('mname')->nullable();
            $table->string('lname')->nullable();
            $table->string('avatar')->default('images/default_avatar.png');
            $table->enum('role', ['Super Admin', 'Admin', 'Branch'])->nullable();
            
            $table->string('email')->unique()->nullable();
            $table->date('birthday')->nullable();
            $table->string('gender')->nullable();
            $table->text('address')->nullable();
            $table->string('contact')->nullable();

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            $table->timestamps();
            $table->softDeletes();
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
