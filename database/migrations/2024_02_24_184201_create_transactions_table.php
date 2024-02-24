<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('loan_id')->nullable();
            $table->string('type');
            $table->float('amount', 8, 2);
            $table->string('trx_number')->nullable();
            $table->string('payment_channel')->nullable();
            $table->date('payment_date');
            
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
        Schema::dropIfExists('transactions');
    }
}
