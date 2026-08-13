<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    /**
     * The authenticated customer's own referrals — who they've referred,
     * and the reward coupon earned for each one that's completed.
     */
    public function myReferrals(Request $request)
    {
        $referrals = Referral::where('referrer_id', $request->user()->id)
            ->with(['referred:id,name,email,created_at', 'rewardOffer'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $referrals,
            'summary' => [
                'pending' => $referrals->where('status', 'pending')->count(),
                'completed' => $referrals->where('status', 'completed')->count(),
            ],
        ]);
    }
}
