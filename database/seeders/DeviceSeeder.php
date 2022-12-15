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
        $ctr = 1;
        $ctr2 = 1;

        for($i = 3; $i <= 5; $i++){
            for($j = 1; $j <= 2; $j++){
                for($k = 1; $k <= 3; $k++){
                    $this->createDevice($i, $ctr, $ctr2);
                    $ctr2++;
                }
                $ctr++;
            }
        }
    }

    public function createDevice($cid, $rid, $sid){
        $data = new Device();
        $data->company_id = $cid;
        $data->route_id = $rid;
        $data->station_id = $sid;
        $data->device_id = substr(md5(uniqid()), 0, 16);
        $data->description = "DEVICE $sid";
        $data->save();
    }
}
