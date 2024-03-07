<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KYC extends Model
{
    protected $fillable = [
        "mobile_number", "fibi_user_id", "document_type", "file"
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];
}
