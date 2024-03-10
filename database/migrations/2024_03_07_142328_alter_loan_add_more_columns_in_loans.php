<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLoanAddMoreColumnsInLoans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->string('source_of_income')->after('payments')->nullable();
            $table->string('use_of_loan')->after('source_of_income')->nullable();

            $table->string('work_name')->after('use_of_loan')->nullable();
            $table->string('work_address')->after('work_name')->nullable();

            $table->string('position')->after('work_address')->nullable();
            $table->string('salary')->after('position')->nullable();
            $table->date('date_of_employment')->after('salary')->nullable();

            $table->string('industry')->after('date_of_employment')->nullable();
            $table->string('capitalization')->after('industry')->nullable();
            $table->string('tin')->after('capitalization')->nullable();

            $table->boolean('eligible')->after('tin')->nullable();
            $table->text('remarks')->after('eligible')->nullable();
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
            $table->dropColumn('source_of_income');
            $table->dropColumn('use_of_loan');
            $table->dropColumn('work_name');
            $table->dropColumn('work_address');
            $table->dropColumn('position');
            $table->dropColumn('salary');
            $table->dropColumn('date_of_employment');
            $table->dropColumn('industry');
            $table->dropColumn('capitalization');
            $table->dropColumn('tin');
            $table->dropColumn('eligible');
            $table->dropColumn('remarks');
        });
    }
}
