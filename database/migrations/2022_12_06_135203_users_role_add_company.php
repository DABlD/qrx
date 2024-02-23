<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UsersRoleAddCompany extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        // Schema::table('users', function (Blueprint $table) {
        //     $table->enum('role', ['Super Admin', 'Admin', 'Coast Guard', 'Company'])->nullable()->change();
        // });

        // \DB::statement("ALTER TABLE `users` CHANGE `role` `role` ENUM(
        //     'Super Admin',
        //     'Admin',
        //     'Coast Guard',
        //     'Company'
        // ) NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
