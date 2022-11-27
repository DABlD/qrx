<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->id();

            // $table->integer('from_station_id');
            // $table->integer('to_station_id');
            $table->string('from');
            $table->string('to');

            $table->string('direction');
            $table->text('stations');
            $table->tinyInteger('base_fare');
            $table->tinyInteger('per_km_fare');

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
        Schema::dropIfExists('routes');
    }
}
