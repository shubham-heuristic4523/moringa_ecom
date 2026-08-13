<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Concerns\ScopesByOwner;
use App\Http\Controllers\Controller;
use App\Models\FlashSale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class FlashSaleController extends Controller
{
    use ScopesByOwner;

    /**
     * List sales (search + status filter). A scoped admin only sees their
     * own; super_admin sees every sale.
     */
    public function index(Request $request)
    {
        $sales = FlashSale::withCount('products')
            ->tap(fn ($query) => $this->applyOwnerScope($query, $request->user()))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('search'), fn ($query) => $query->where('name', 'like', '%' . $request->search . '%'))
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return response()->json([
            'status' => true,
            'data' => $sales,
        ]);
    }

    /**
     * Create a sale, owned by the creating admin, with the picked products.
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

        $data = $validator->validated();

        $sale = FlashSale::create([
            'owner_id' => $request->user()->id,
            'name' => $data['name'],
            'starts_at' => $data['starts_at'] ?? null,
            'ends_at' => $data['ends_at'],
            'status' => $data['status'] ?? 'active',
        ]);

        $sale->products()->sync($data['product_ids']);

        return response()->json([
            'status' => true,
            'message' => 'Sale created successfully.',
            'data' => $sale->load('products'),
        ], 201);
    }

    /**
     * Show a single sale with its products.
     */
    public function show(Request $request, $id)
    {
        $sale = FlashSale::with('products')->find($id);

        if (! $sale || ! $this->canAccess($sale, $request->user())) {
            return response()->json([
                'status' => false,
                'message' => 'Sale not found.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $sale,
        ]);
    }

    /**
     * Update a sale's name/schedule/status and swap its products.
     */
    public function update(Request $request, $id)
    {
        $sale = FlashSale::find($id);

        if (! $sale || ! $this->canAccess($sale, $request->user())) {
            return response()->json([
                'status' => false,
                'message' => 'Sale not found.',
            ], 404);
        }

        $validator = Validator::make($request->all(), $this->rules($request, sometimes: true));

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        $sale->update(array_filter([
            'name' => $data['name'] ?? null,
            'starts_at' => array_key_exists('starts_at', $data) ? $data['starts_at'] : null,
            'ends_at' => $data['ends_at'] ?? null,
            'status' => $data['status'] ?? null,
        ], fn ($value) => $value !== null));

        if (array_key_exists('product_ids', $data)) {
            $sale->products()->sync($data['product_ids']);
        }

        return response()->json([
            'status' => true,
            'message' => 'Sale updated successfully.',
            'data' => $sale->fresh('products'),
        ]);
    }

    /**
     * Delete a sale (its product associations go with it).
     */
    public function destroy(Request $request, $id)
    {
        $sale = FlashSale::find($id);

        if (! $sale || ! $this->canAccess($sale, $request->user())) {
            return response()->json([
                'status' => false,
                'message' => 'Sale not found.',
            ], 404);
        }

        $sale->delete();

        return response()->json([
            'status' => true,
            'message' => 'Sale deleted successfully.',
        ]);
    }

    /**
     * Validation rules shared by store/update. product_ids must belong to
     * the creating admin's own catalog unless they're super_admin.
     */
    private function rules(Request $request, bool $sometimes = false): array
    {
        $required = $sometimes ? 'sometimes|required' : 'required';

        return [
            'name' => "{$required}|string|max:255",
            'starts_at' => 'nullable|date',
            'ends_at' => "{$required}|date|after_or_equal:starts_at",
            'status' => 'sometimes|in:active,inactive',

            'product_ids' => "{$required}|array|min:1",
            'product_ids.*' => [
                'integer',
                $this->isScopedAdmin($request->user())
                    ? Rule::exists('products', 'id')->where('owner_id', $request->user()?->id)
                    : Rule::exists('products', 'id'),
            ],
        ];
    }
}
