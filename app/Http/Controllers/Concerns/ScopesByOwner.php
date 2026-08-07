<?php

namespace App\Http\Controllers\Concerns;

use App\Models\User;

/**
 * Shared "each admin only sees their own data" scoping, used across the
 * admin-facing product/order/customer/offer/referral controllers.
 *
 * super_admin is never scoped — it retains full, platform-wide visibility.
 */
trait ScopesByOwner
{
    /**
     * Whether this user's queries should be restricted to their own data.
     */
    protected function isScopedAdmin(?User $user): bool
    {
        return $user !== null && $user->role === 'admin';
    }

    /**
     * Apply the "owned by me" filter to a query, unless the user is a
     * super_admin (or a guest, for public routes that allow no auth).
     */
    protected function applyOwnerScope($query, ?User $user, string $column = 'owner_id')
    {
        if ($this->isScopedAdmin($user)) {
            $query->where($column, $user->id);
        }

        return $query;
    }

    /**
     * Whether this user is allowed to see/act on a single already-loaded
     * model. Guests and super_admin always pass; a scoped admin only
     * passes if they own the record.
     */
    protected function canAccess($model, ?User $user, string $column = 'owner_id'): bool
    {
        if (! $this->isScopedAdmin($user)) {
            return true;
        }

        return (int) $model->{$column} === (int) $user->id;
    }
}
