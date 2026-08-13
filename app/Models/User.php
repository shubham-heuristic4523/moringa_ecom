<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Customer\CustomerProfile;
use App\Models\Customer\CustomerAddress;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'role',
        'status',
        'referral_code',
        'store_slug',
        'registered_via_admin_id',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function customerProfile(): HasOne
    {
        return $this->hasOne(CustomerProfile::class);
    }
        public function customerAddresses()
    {
        return $this->hasMany(CustomerAddress::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Products this admin owns (created), for analytics/listing.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'owner_id');
    }

    /**
     * Customers who registered on this admin's storefront.
     */
    public function registeredCustomers(): HasMany
    {
        return $this->hasMany(User::class, 'registered_via_admin_id');
    }

    /**
     * Referrals this user has made as the referrer.
     */
    public function referralsMade(): HasMany
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    /**
     * The referral record for how this user was referred in, if any.
     */
    public function referredBy(): HasOne
    {
        return $this->hasOne(Referral::class, 'referred_id');
    }

    /**
     * The admin whose storefront this customer registered on, if any.
     */
    public function registeredViaAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_via_admin_id');
    }

    protected static function booted(): void
    {
        static::creating(function (User $user) {
            if (empty($user->referral_code)) {
                do {
                    $code = 'REF' . strtoupper(Str::random(6));
                } while (static::where('referral_code', $code)->exists());

                $user->referral_code = $code;
            }

            if (empty($user->store_slug) && in_array($user->role, ['admin', 'super_admin'], true)) {
                $user->store_slug = static::generateUniqueStoreSlug($user->name);
            }
        });
    }

    public static function generateUniqueStoreSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'store';
        $slug = $base;
        $suffix = 1;

        while (static::where('store_slug', $slug)->exists()) {
            $slug = $base . '-' . $suffix++;
        }

        return $slug;
    }
}
