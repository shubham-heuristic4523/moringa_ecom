<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WishlistController extends Controller
{
    /**
     * The authenticated customer's wishlist.
     */
    public function index(Request $request)
    {
        $wishlist = Wishlist::with('product.category')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Wishlist fetched successfully.',
            'data' => $wishlist,
        ]);
    }

    /**
     * Add a product to the authenticated customer's wishlist.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $exists = Wishlist::where('user_id', $request->user()->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($exists) {
            return response()->json([
                'status' => false,
                'message' => 'Product already exists in wishlist.',
            ], 409);
        }

        $wishlist = Wishlist::create([
            'user_id' => $request->user()->id,
            'product_id' => $request->product_id,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Product added to wishlist.',
            'data' => $wishlist->load('product.category'),
        ], 201);
    }

    /**
     * Remove a wishlist entry, by its own id or by product_id (whichever
     * the caller has handy) — but only if it belongs to the caller.
     */
    public function destroy(Request $request, $id)
    {
        $wishlist = Wishlist::where('user_id', $request->user()->id)
            ->where(fn ($query) => $query->where('id', $id)->orWhere('product_id', $id))
            ->first();

        if (! $wishlist) {
            return response()->json([
                'status' => false,
                'message' => 'Wishlist item not found.',
            ], 404);
        }

        $wishlist->delete();

        return response()->json([
            'status' => true,
            'message' => 'Product removed from wishlist.',
        ]);
    }
}
