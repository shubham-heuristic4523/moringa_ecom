<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FlashSale;
use App\Models\User;
use Illuminate\Http\Request;

class FlashSaleController extends Controller
{
    /**
     * Public: the currently-active flash sale (soonest-ending first) with
     * its products, for the storefront's Flash Sale section. Pass
     * ?store={slug} for a specific admin's storefront, or ?owner_id=
     * directly; omit both for the platform-wide default.
     */
    public function active(Request $request)
    {
        $ownerId = $request->filled('owner_id') ? (int) $request->query('owner_id') : null;

        if ($request->filled('store')) {
            $admin = User::where('store_slug', $request->query('store'))
                ->whereIn('role', ['admin', 'super_admin'])
                ->first();

            $ownerId = $admin?->id;
        }

        // Matches SettingController's convention: the platform-wide default
        // ("/", no store/owner given) shows the super_admin's own sales.
        if (! $ownerId) {
            $ownerId = User::where('role', 'super_admin')->value('id');
        }

        $sale = FlashSale::with(['products' => function ($query) {
                $query->with(['category', 'brand', 'images'])->withCount('variants');
            }])
            ->where('status', 'active')
            ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where('ends_at', '>=', now())
            ->when($ownerId, fn ($q) => $q->where('owner_id', $ownerId))
            ->orderBy('ends_at')
            ->first();

        return response()->json([
            'status' => true,
            'data' => $sale,
        ]);
    }
}
