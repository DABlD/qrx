<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Device;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 1; $i <= 2; $i++){
            for($j = 1; $j <= 5; $j++){
                $this->createDevice($i, ($j + (($i - 1) * 5)));
            }
        }
    }

    public function createDevice($rid, $sid){
        $data = new Device();
        $data->route_id = $rid;
        $data->station_id = $sid;
        $data->device_id = substr(md5(uniqid()), 0, 16);
        $data->description = "DEVICE $sid";
        $data->save();
    }
}
