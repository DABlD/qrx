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

            $ctr = 1;
            for($i = 3; $i <= 5; $i++){
                for($J = 1; $J <= 3; $J++){
                    $this->createVehicle($i, $type, $ctr);
                }
            }
        }
    }

    public function createVehicle($cid, $type, $ctr){
        $data = new Vehicle();
        $data->company_id = $cid;
        $data->vehicle_id = $type . " " . $ctr;
        $data->type = $type;
        $data->passenger_limit = rand(30, 50);
        $data->save();
    }
}
