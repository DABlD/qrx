<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\{AuditTrail};

class AuditTrail extends Model
{
    protected $fillable = [
        'uid','action','description',
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];
}