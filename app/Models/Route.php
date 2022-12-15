<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\RouteAttribute;
use App\Models\{Route, Station};

class Route extends Model
{
    use SoftDeletes, RouteAttribute;
    
    protected $fillable = [
        'company_id', 'from','to','direction','stations','base_fare','per_km_fare'
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function stations(){
        return $this->hasMany(Station::class, 'route_id', 'id');
    }
}