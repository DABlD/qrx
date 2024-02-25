<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\LoanAttribute;

class Loan extends Model
{
    use LoanAttribute;
    
    protected $fillable = [
        "branch_id","amount","percent","months",
        "balance","status","credited","payment_channel","paid_months", "reference",
        'contract_no', 'type', 'collateral1', 'collateral2', 'collateral3', 'payments'
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    public function branch(){
        return $this->belongsTo('App\Models\Branch');
    }

    public function transactions(){
        return $this->hasMany('App\Models\Transaction');
    }
}