<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        "user_id","loan_id","type","amount","trx_number","payment_channel","payment_date"
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    public function loan(){
        return $this->belongsTo('App\Models\Loan');
    }
}