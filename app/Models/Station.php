<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\StationAttribute;
use App\Models\{Station};

class Station extends Model
{
    use SoftDeletes, StationAttribute;
    
    protected $fillable = [
        'name','label','kilometer',
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];
}