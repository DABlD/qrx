<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\UserAttribute;

use Hash;

class User extends Authenticatable
{
    use SoftDeletes, UserAttribute;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role', 'fname', 'mname', 
        'lname', 'email', 'birthday', 
        'gender', 'address', 'contact', 
        'password', 'email_verified_at',
        'username',
        'user_id', 'civil_status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at', 'birthday', 'email_verified_at'
    ];

    public function setPasswordAttribute($value) {
        $this->attributes['password'] = Hash::make($value);
    }
}