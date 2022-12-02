
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();

            $table->json('user');
            $table->unsignedInteger('origin_id');
            $table->unsignedInteger('destination_id');
            $table->unsignedInteger('vehicle_id');

            $table->string('ticket');
            $table->unsignedInteger('ticket_no');
            $table->float('amount', 8, 2);
            $table->string('status')->default('Ticket Generated');
            $table->datetime('embarked_date')->nullable();

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
        Schema::dropIfExists('sales');
    }
}
