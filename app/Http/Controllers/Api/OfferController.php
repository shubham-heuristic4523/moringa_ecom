<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    /**
     * Public: currently-active offers (status active + within their
     * scheduled window), for storefront banners/flash-sale sections.
     * Pass ?owner_id= to scope to one admin's storefront.
     */
    public function active(Request $request)
    {
        $offers = Offer::with(['category', 'product'])
            ->where('status', 'active')
            ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', now()))
            ->when($request->filled('owner_id'), fn ($q) => $q->where('owner_id', $request->owner_id))
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->type))
            ->latest()
            ->limit($request->integer('limit', 6))
            ->get();

        return response()->json([
            'status' => true,
            'data' => $offers,
        ]);
    }
}
