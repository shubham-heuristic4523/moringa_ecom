<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Concerns\ScopesByOwner;
use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class OfferController extends Controller
{
    use ScopesByOwner;

    /**
     * List all offers (search + type/status filters).
     */
    public function index(Request $request)
    {
        $offers = Offer::with(['category', 'product', 'owner'])
            ->tap(fn ($query) => $this->applyOwnerScope($query, $request->user()))
            ->when($request->filled('type'), fn ($query) => $query->where('type', $request->type))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($inner) use ($request) {
                    $inner->where('title', 'like', '%' . $request->search . '%')
                        ->orWhere('code', 'like', '%' . $request->search . '%');
                });
            })
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return response()->json([
            'status' => true,
            'message' => 'Offers fetched successfully.',
            'data' => $offers,
        ]);
    }

    /**
     * Create a new offer, owned by the creating admin.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules($request));

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $offer = Offer::create($this->normalize($validator->validated()) + [
            'owner_id' => $request->user()->id,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Offer created successfully.',
            'data' => $offer->load(['category', 'product']),
        ], 201);
    }

    /**
     * Show a single offer.
     */
    public function show(Request $request, $id)
    {
        $offer = Offer::with(['category', 'product'])->find($id);

        if (! $offer || ! $this->canAccess($offer, $request->user())) {
            return response()->json([
                'status' => false,
                'message' => 'Offer not found.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $offer,
        ]);
    }

    /**
     * Update an offer.
     */
    public function update(Request $request, $id)
    {
        $offer = Offer::find($id);

        if (! $offer || ! $this->canAccess($offer, $request->user())) {
            return response()->json([
                'status' => false,
                'message' => 'Offer not found.',
            ], 404);
        }

        $validator = Validator::make($request->all(), $this->rules($request, $offer->id));

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $offer->update($this->normalize($validator->validated(), $offer));

        return response()->json([
            'status' => true,
            'message' => 'Offer updated successfully.',
            'data' => $offer->load(['category', 'product']),
        ]);
    }

    /**
     * Delete an offer.
     */
    public function destroy(Request $request, $id)
    {
        $offer = Offer::find($id);

        if (! $offer || ! $this->canAccess($offer, $request->user())) {
            return response()->json([
                'status' => false,
                'message' => 'Offer not found.',
            ], 404);
        }

        $offer->delete();

        return response()->json([
            'status' => true,
            'message' => 'Offer deleted successfully.',
        ]);
    }

    /**
     * Validation rules shared by store/update.
     */
    private function rules(Request $request, ?int $offerId = null): array
    {
        $sometimes = $offerId !== null;
        $required = $sometimes ? 'sometimes|required' : 'required';

        return [
            'title' => "{$required}|string|max:255",
            'description' => 'nullable|string',

            'type' => "{$required}|in:coupon,auto_discount",
            'code' => [
                Rule::requiredIf(fn () => $request->input('type') === 'coupon'),
                'nullable',
                'string',
                'max:50',
                'alpha_dash',
                Rule::unique('offers', 'code')->ignore($offerId),
            ],

            'discount_type' => "{$required}|in:percentage,flat",
            'discount_value' => [
                $required, 'numeric', 'min:0.01',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->input('discount_type') === 'percentage' && $value > 100) {
                        $fail('Percentage discount cannot exceed 100.');
                    }
                },
            ],
            'max_discount_amount' => 'nullable|numeric|min:0.01',
            'min_order_amount' => 'nullable|numeric|min:0',

            'scope' => "{$required}|in:all,category,product",
            'category_id' => [Rule::requiredIf(fn () => $request->input('scope') === 'category'), 'nullable', 'exists:categories,id'],
            'product_id' => [
                Rule::requiredIf(fn () => $request->input('scope') === 'product'),
                'nullable',
                $this->isScopedAdmin($request->user())
                    ? Rule::exists('products', 'id')->where('owner_id', $request->user()?->id)
                    : 'exists:products,id',
            ],

            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',

            'status' => "{$required}|in:active,inactive",
        ];
    }

    /**
     * Clear whichever fields don't apply to the offer's type/scope, and
     * uppercase coupon codes for consistent lookups.
     */
    private function normalize(array $data, ?Offer $offer = null): array
    {
        $type = $data['type'] ?? $offer?->type;
        $scope = $data['scope'] ?? $offer?->scope;

        if ($type === 'coupon') {
            if (isset($data['code'])) {
                $data['code'] = strtoupper($data['code']);
            }
        } else {
            $data['code'] = null;
        }

        if ($scope !== 'category') {
            $data['category_id'] = null;
        }

        if ($scope !== 'product') {
            $data['product_id'] = null;
        }

        return $data;
    }
}
