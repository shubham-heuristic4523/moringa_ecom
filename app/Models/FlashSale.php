<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FlashSale extends Model
{
    protected $fillable = [
        'owner_id',
        'name',
        'starts_at',
        'ends_at',
        'status',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    protected $appends = [
        'effective_status',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'flash_sale_product')->withTimestamps();
    }

    /**
     * Whether this sale is live right now: enabled by the admin and within
     * its scheduled window.
     */
    public function isCurrentlyActive(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        $now = now();

        if ($this->starts_at && $now->lt($this->starts_at)) {
            return false;
        }

        return $this->ends_at && $now->lte($this->ends_at);
    }

    /**
     * Richer status for display: distinguishes an admin-disabled sale from
     * one that simply hasn't started yet or has already ended.
     */
    public function getEffectiveStatusAttribute(): string
    {
        if ($this->status !== 'active') {
            return 'inactive';
        }

        $now = now();

        if ($this->starts_at && $now->lt($this->starts_at)) {
            return 'scheduled';
        }

        if ($this->ends_at && $now->gt($this->ends_at)) {
            return 'ended';
        }

        return 'active';
    }
}
