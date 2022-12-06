<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\AdAttribute;
use App\Models\{Device};

class Ad extends Model
{
    use SoftDeletes, AdAttribute;
    
    protected $fillable = [
        'title','description','url','type',
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function devices(){
        return $this->hasMany(Device::class, 'ad_id', 'id');
    }
}