<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\StationAttribute;
use App\Models\{Station, Route};

class Station extends Model
{
    use SoftDeletes, StationAttribute;
    
    protected $fillable = [
        'name','label','kilometer', 'lat', 'lng'
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function route(){
        return $this->belongsTo(Route::class, 'route_id', 'id');
    }
}