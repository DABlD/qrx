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
        for($i = 3; $i <= 5; $i++){
            $this->createRoute("EDSA", "QUIRINO", "NORTHBOUND", $i, $i - 2);
            $this->createRoute("QUIRINO", "EDSA", "SOUTHBOUND", $i, $i - 2);
        }
    }

    public function createRoute($from, $to, $direction, $cid, $multiplier){
        $data = new Route();
        $data->company_id = $cid;
        $data->from = $from;
        $data->to = $to;
        $data->direction = $direction;
        $data->stations = "[]";
        $data->base_fare = 10 * $multiplier;
        $data->per_km_fare = 2 * $multiplier;
        $data->save();
    }
}
