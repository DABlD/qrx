<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Station;

class StationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = ["EDSA", 'LIBERTAD', 'GIL PUYAT', 'VITO CRUZ', 'QUIRINO'];
        $array2 = array_reverse($array);

        foreach($array as $km => $station){
            $this->createStation(1, $km, $station);
        }

        foreach($array2 as $km => $station){
            $this->createStation(2, $km, $station);
        }
    }

    public function createStation($id, $km, $station){
        $data = new Station();
        $data->route_id = $id;
        $data->name = $station;
        $data->label = "STATION";
        $data->kilometer = $km;
        $data->save();
    }
}
