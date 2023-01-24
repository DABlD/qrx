<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\LedgerAttribute;
use App\Models\{Sale, Device};

class Ledger extends Model
{
    use SoftDeletes, LedgerAttribute;
    
    protected $fillable = [
        'device_id', 'sale_id', 'amount', 'trx_type', 'description'
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at', 'datetime'
    ];

    public function device(){
        return $this->hasOne(Device::class, 'id', 'device_id');
    }

    public function sale(){
        return $this->hasOne(Sale::class, 'id', 'sale_id');
    }
}