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
        'contract_no', 'type', 'collateral1', 'collateral2', 'collateral3', 'payments',
        'file1', 'file2', 'file3', 'date_disbursed',

        "source_of_income","use_of_loan", "repayment_plan", "work_name",
        "work_address", "type_of_organization","position","salary",
        "date_of_employment","industry","capitalization","tin",
        "eligible","remarks"
    ];

    protected $dates = [
        'created_at', 'updated_at', 'date_of_employment'
    ];

    public function branch(){
        return $this->belongsTo('App\Models\Branch')->withTrashed();
    }

    public function transactions(){
        return $this->hasMany('App\Models\Transaction');
    }
}