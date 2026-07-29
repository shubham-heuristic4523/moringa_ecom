<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerProfile extends Model
{
    protected $fillable = [
        'user_id',
        'gender',
        'date_of_birth',
        'profile_photo',
        'company_name',
        'gst_number',
        'bio',
        'profile_completed',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'profile_completed' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}