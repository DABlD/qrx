<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();

            $table->enum('status', ['Applied', 'Approved', 'Disapproved', 'For Payment', 'Overdue', 'Paid'])->nullable()->default("Applied");
            $table->unsignedInteger('branch_id');
            $table->float('amount', 8,2);
            $table->float('percent', 4, 2);
            $table->float('balance', 8,2)->nullable();
            $table->unsignedSmallInteger('months');
            $table->unsignedSmallInteger('paid_months')->default(0);
            $table->boolean('credited')->default(0);
            $table->string('payment_channel')->nullable();

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
        Schema::dropIfExists('loans');
    }
}
