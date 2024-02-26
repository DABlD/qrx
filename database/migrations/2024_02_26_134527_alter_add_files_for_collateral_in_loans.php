<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAddFilesForCollateralInLoans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->string('file1')->after('collateral1')->nullable();
            $table->string('file2')->after('collateral2')->nullable();
            $table->string('file3')->after('collateral3')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn('file1');
            $table->dropColumn('file2');
            $table->dropColumn('file3');
        });
    }
}
