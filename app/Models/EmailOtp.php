<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailOtp extends Model
{
    protected $fillable = [
        'user_id',
        'email',
        'otp',
        'purpose',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];
}