<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Route;

class RouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createRoute("EDSA", "QUIRINO", "NORTHBOUND");
        $this->createRoute("QUIRINO", "EDSA", "SOUTHBOUND");
    }

    public function createRoute($from, $to, $direction){
        $data = new Route();
        $data->from = $from;
        $data->to = $to;
        $data->direction = $direction;
        $data->stations = "[]";
        $data->base_fare = 10;
        $data->per_km_fare = 2;
        $data->save();
    }
}
