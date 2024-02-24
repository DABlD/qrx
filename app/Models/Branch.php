<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\BranchAttribute;

class Branch extends Model
{
    use BranchAttribute;
    
    protected $fillable = [
        "user_id","work_status","id_type","id_num","id_file","id_verified","percent"
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}