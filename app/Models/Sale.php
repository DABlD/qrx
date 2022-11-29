<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\SaleAttribute;
use App\Models\{Sale, Station, User};

class Route extends Model
{
    use SoftDeletes, SaleAttribute;
    
    protected $fillable = [
        'origin_id','destination_id','vehicle_id','user_id','ticket','amount','status','embarked_date',
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at', 'embarked_date'
    ];

    public function origin(){
        return $this->hasOne(Station::class, 'id', 'origin_id');
    }

    public function destination(){
        return $this->hasOne(Station::class, 'id', 'destination_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

    public function vehicle(){
        return $this->hasOne(Vehicle::class, 'id', 'vehicle_id');
    }
}