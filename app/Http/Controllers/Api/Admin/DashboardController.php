<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Concerns\ScopesByOwner;
use App\Http\Controllers\Controller;
use App\Models\FlashSale;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    use ScopesByOwner;

    private const NON_REVENUE_STATUSES = ['cancelled', 'returned', 'refunded'];

    /**
     * Rollup stats + recent activity for the admin dashboard. Scoped to the
     * logged-in admin's own storefront; unscoped for super_admin.
     */
    public function stats(Request $request)
    {
        $user = $request->user();
        $ownerId = $this->isScopedAdmin($user) ? $user->id : null;

        $orders = Order::query()->tap(fn ($q) => $this->applyOwnerScope($q, $user, 'admin_id'));

        $revenue = (clone $orders)->whereNotIn('status', self::NON_REVENUE_STATUSES)->sum('total');
        $ordersCount = (clone $orders)->count();
        // Matches the "pending" option in the orders page's status filter,
        // so the dashboard card's link filters to exactly what it counted.
        $pendingCount = (clone $orders)->where('status', 'pending')->count();

        $productsCount = Product::query()->tap(fn ($q) => $this->applyOwnerScope($q, $user))->count();

        $customersCount = User::where('role', 'user')
            ->when($ownerId, function ($query) use ($ownerId) {
                $query->where(function ($outer) use ($ownerId) {
                    $outer->where('registered_via_admin_id', $ownerId)
                        ->orWhereHas('orders', fn ($q) => $q->where('admin_id', $ownerId));
                });
            })
            ->count();

        $lowStockProductIds = DB::table('product_variants')
            ->join('products', 'products.id', '=', 'product_variants.product_id')
            ->when($ownerId, fn ($q) => $q->where('products.owner_id', $ownerId))
            ->groupBy('product_variants.product_id')
            ->havingRaw('SUM(product_variants.stock) <= 10')
            ->pluck('product_variants.product_id');

        $activeOffersCount = Offer::query()
            ->tap(fn ($q) => $this->applyOwnerScope($q, $user))
            ->where('status', 'active')
            ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', now()))
            ->count();

        $activeFlashSalesCount = FlashSale::query()
            ->tap(fn ($q) => $this->applyOwnerScope($q, $user))
            ->where('status', 'active')
            ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where('ends_at', '>=', now())
            ->count();

        $recentOrders = (clone $orders)
            ->with('user:id,name,email')
            ->latest()
            ->limit(6)
            ->get(['id', 'order_number', 'user_id', 'total', 'status', 'created_at']);

        return response()->json([
            'status' => true,
            'data' => [
                'products_count' => $productsCount,
                'orders_count' => $ordersCount,
                'customers_count' => $customersCount,
                'revenue' => (float) $revenue,
                'pending_orders_count' => $pendingCount,
                'low_stock_count' => $lowStockProductIds->count(),
                'active_offers_count' => $activeOffersCount,
                'active_flash_sales_count' => $activeFlashSalesCount,
                'recent_orders' => $recentOrders,
            ],
        ]);
    }
}
