<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'owner_id',
        'site_name',
        'tagline',
        'logo',
        'primary_color',
        'secondary_color',
        'accent_color',
        'referral_discount_type',
        'referral_discount_value',
        'referral_max_discount_amount',
        'referral_validity_days',
    ];

    protected $casts = [
        'referral_discount_value' => 'decimal:2',
        'referral_max_discount_amount' => 'decimal:2',
        'referral_validity_days' => 'integer',
    ];

    protected $appends = [
        'logo_url',
    ];

    /**
     * Each admin gets their own branding + referral-reward row, created on
     * first access with sensible defaults. The main site ("/") shows the
     * super_admin's row; passing null falls back to a platform-wide default
     * row, used only if no super_admin exists.
     */
    public static function forAdmin(?int $ownerId): self
    {
        return static::query()->firstOrCreate(['owner_id' => $ownerId], [
            'site_name' => 'Moringa',
            'tagline' => 'Pure, natural moringa products for everyday wellness.',
            'primary_color' => '#3E8E5B',
            'secondary_color' => '#F5A623',
            'accent_color' => '#2C6E49',
            'referral_discount_type' => 'percentage',
            'referral_discount_value' => 10,
            'referral_max_discount_amount' => 200,
            'referral_validity_days' => 30,
        ]);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo ? asset('storage/' . $this->logo) : null;
    }
}
