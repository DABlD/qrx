<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\DeviceAttribute;
use App\Models\{Device, Route, Station};

class Device extends Model
{
    use SoftDeletes, DeviceAttribute;
    
    protected $fillable = [
        'station_id','route_id','device_id','status','description'
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function route(){
        return $this->belongsTo(Route::class);
    }

    public function station(){
        return $this->belongsTo(Station::class);
    }
}