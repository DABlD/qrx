<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Vehicle;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = ["Bus", "Ferry"];

        foreach($types as $type){
            for($i = 1; $i <= 3; $i++){
                $this->createVehicle($type, $i);
            }
        }
    }

    public function createVehicle($type, $i){
        $data = new Vehicle();
        $data->vehicle_id = $type . " " . $i;
        $data->type = $type;
        $data->passenger_limit = rand(30, 50);
        $data->save();
    }
}
