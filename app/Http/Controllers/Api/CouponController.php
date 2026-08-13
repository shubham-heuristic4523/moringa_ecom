<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Validate a coupon code against the current cart subtotal and return
     * the discount it would apply. Does not place an order — the same
     * code is re-validated server-side again when the order is created.
     */
    public function validate(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $offer = Offer::where('type', 'coupon')
            ->where('code', strtoupper(trim($request->code)))
            ->first();

        if (! $offer) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid coupon code.',
            ], 404);
        }

        if (! $offer->isCurrentlyActive()) {
            return response()->json([
                'status' => false,
                'message' => 'This coupon has expired or is not currently active.',
            ], 422);
        }

        if ($offer->min_order_amount && $request->subtotal < $offer->min_order_amount) {
            return response()->json([
                'status' => false,
                'message' => 'This coupon requires a minimum order of ₹' . number_format((float) $offer->min_order_amount, 2) . '.',
            ], 422);
        }

        $discount = $offer->calculateDiscount((float) $request->subtotal);

        return response()->json([
            'status' => true,
            'message' => 'Coupon applied successfully.',
            'data' => [
                'offer_id' => $offer->id,
                'code' => $offer->code,
                'title' => $offer->title,
                'discount' => $discount,
            ],
        ]);
    }
}
