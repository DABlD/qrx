<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\LoanAttribute;

class Loan extends Model
{
    use LoanAttribute;
    
    protected $fillable = [
        "branch_id","amount","percent","balance","months","status",
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    public function branch(){
        return $this->belongsTo('App\Models\Branch');
    }
}