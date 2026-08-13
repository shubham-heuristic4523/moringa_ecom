<?php

namespace App\Http\Controllers\Api\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Concerns\ScopesByOwner;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    use ScopesByOwner;

    /**
     * Products ranked by units sold, most-ordered first. Scoped to "my
     * products" for a regular admin; unscoped (platform-wide) for super_admin.
     */
    public function bestSellers(Request $request)
    {
        $excluded = [OrderStatus::CANCELLED->value, OrderStatus::RETURNED->value, OrderStatus::REFUNDED->value];

        $products = Product::query()
            ->with(['owner:id,name', 'category', 'brand'])
            ->withSum(['orderItems as units_sold' => function ($query) use ($excluded) {
                $query->whereHas('order', fn ($q) => $q->whereNotIn('status', $excluded));
            }], 'quantity')
            ->withSum(['orderItems as revenue' => function ($query) use ($excluded) {
                $query->whereHas('order', fn ($q) => $q->whereNotIn('status', $excluded));
            }], 'line_total')
            ->tap(fn ($query) => $this->applyOwnerScope($query, $request->user()))
            ->orderByDesc('units_sold')
            ->orderByDesc('revenue')
            ->paginate($request->integer('per_page', 20));

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }
}
