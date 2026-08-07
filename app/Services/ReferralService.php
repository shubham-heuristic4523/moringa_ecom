<?php

namespace App\Services;

use App\Models\Offer;
use App\Models\Order;
use App\Models\Referral;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReferralService
{
    /**
     * Reward the referrer once the person they referred completes their
     * first delivered order. No-op if this order's customer wasn't
     * referred, their referral is already completed, or this isn't
     * actually their first delivered order.
     */
    public function handleOrderDelivered(Order $order): void
    {
        $referral = Referral::where('referred_id', $order->user_id)
            ->where('status', 'pending')
            ->first();

        if (! $referral) {
            return;
        }

        $deliveredOrderCount = Order::where('user_id', $order->user_id)
            ->where('status', 'delivered')
            ->count();

        if ($deliveredOrderCount > 1) {
            return;
        }

        DB::transaction(function () use ($referral, $order) {
            $offer = $this->issueRewardCoupon($referral, $order->admin_id);

            $referral->update([
                'status' => 'completed',
                'admin_id' => $order->admin_id,
                'reward_offer_id' => $offer->id,
                'completed_at' => now(),
            ]);
        });
    }

    /**
     * Auto-generate a one-time coupon for the referrer, using the
     * reward configured in config/referrals.php. Owned by whichever
     * admin's storefront the qualifying order was placed with.
     */
    private function issueRewardCoupon(Referral $referral, ?int $ownerId): Offer
    {
        $referrer = $referral->referrer;
        $discountType = config('referrals.discount_type', 'percentage');

        do {
            $code = 'REF' . $referrer->id . strtoupper(Str::random(4));
        } while (Offer::where('code', $code)->exists());

        return Offer::create([
            'owner_id' => $ownerId,
            'title' => 'Referral Reward for ' . $referrer->name,
            'description' => 'Automatically issued for referring a customer who completed their first order.',
            'type' => 'coupon',
            'code' => $code,
            'discount_type' => $discountType,
            'discount_value' => config('referrals.discount_value', 10),
            'max_discount_amount' => $discountType === 'percentage' ? config('referrals.max_discount_amount') : null,
            'scope' => 'all',
            'starts_at' => now(),
            'ends_at' => now()->addDays((int) config('referrals.validity_days', 30)),
            'status' => 'active',
        ]);
    }
}
