<?php

namespace App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    protected $fillable = [

        'user_id',

        'full_name',

        'phone',

        'alternate_phone',

        'address_type',

        'address_line_1',

        'address_line_2',

        'landmark',

        'city',

        'state',

        'country',

        'postal_code',

        'is_default'

    ];

    protected $casts = [

        'is_default' => 'boolean'

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}