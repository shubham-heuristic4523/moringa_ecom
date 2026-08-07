<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Concerns\ScopesByOwner;
use App\Http\Controllers\Controller;
use App\Models\Referral;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    use ScopesByOwner;

    /**
     * A referral is "mine" if it was completed via one of my orders, or
     * (while still pending) if the referrer has bought from me before.
     */
    private function scopeToAdmin($query, int $adminId)
    {
        return $query->where(function ($outer) use ($adminId) {
            $outer->where('admin_id', $adminId)
                ->orWhereHas('referrer.orders', fn ($q) => $q->where('admin_id', $adminId));
        });
    }

    /**
     * List all referrals (search + status filter), with summary counts.
     */
    public function index(Request $request)
    {
        $request->validate([
            'status' => 'nullable|in:pending,completed',
            'search' => 'nullable|string|max:255',
        ]);

        $adminId = $this->isScopedAdmin($request->user()) ? $request->user()->id : null;

        $referrals = Referral::with(['referrer', 'referred', 'rewardOffer'])
            ->when($adminId, fn ($query) => $this->scopeToAdmin($query, $adminId))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($inner) use ($request) {
                    $inner->where('referral_code', 'like', '%' . $request->search . '%')
                        ->orWhereHas('referrer', function ($userQuery) use ($request) {
                            $userQuery->where('name', 'like', '%' . $request->search . '%')
                                ->orWhere('email', 'like', '%' . $request->search . '%');
                        })
                        ->orWhereHas('referred', function ($userQuery) use ($request) {
                            $userQuery->where('name', 'like', '%' . $request->search . '%')
                                ->orWhere('email', 'like', '%' . $request->search . '%');
                        });
                });
            })
            ->latest()
            ->paginate($request->integer('per_page', 15));

        $summaryBase = Referral::query()->when($adminId, fn ($query) => $this->scopeToAdmin($query, $adminId));

        return response()->json([
            'success' => true,
            'data' => $referrals,
            'summary' => [
                'total' => (clone $summaryBase)->count(),
                'pending' => (clone $summaryBase)->where('status', 'pending')->count(),
                'completed' => (clone $summaryBase)->where('status', 'completed')->count(),
            ],
        ]);
    }

    /**
     * Show a single referral.
     */
    public function show(Request $request, $id)
    {
        $adminId = $this->isScopedAdmin($request->user()) ? $request->user()->id : null;

        $referral = Referral::with(['referrer', 'referred', 'rewardOffer'])
            ->when($adminId, fn ($query) => $this->scopeToAdmin($query, $adminId))
            ->find($id);

        if (! $referral) {
            return response()->json([
                'success' => false,
                'message' => 'Referral not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $referral,
        ]);
    }
}
