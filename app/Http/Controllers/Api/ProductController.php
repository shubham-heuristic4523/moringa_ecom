<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Concerns\ScopesByOwner;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    use ScopesByOwner;

    /**
     * Display all products. Scoped to "my products" for a logged-in admin;
     * unscoped for super_admin and for guests browsing the public catalog.
     */
    public function index(Request $request)
    {
        $products = Product::with(['category', 'brand', 'images'])
            ->withCount('variants')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('sku', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->filled('category_id'), fn ($query) => $query->where('category_id', $request->category_id))
            ->when($request->filled('brand_id'), fn ($query) => $query->where('brand_id', $request->brand_id))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->tap(fn ($query) => $this->applyOwnerScope($query, $request->user('sanctum')))
            ->latest()
            ->paginate($request->integer('per_page', 10));

        return response()->json([
            'status' => true,
            'message' => 'Products fetched successfully.',
            'data' => $products,
        ]);
    }

    /**
     * Store a new product, owned by the creating admin.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        DB::beginTransaction();

        try {
            $product = Product::create($this->corefields($data) + [
                'owner_id' => $request->user()->id,
            ]);

            if ($request->hasFile('thumbnail')) {
                $product->update([
                    'thumbnail' => $request->file('thumbnail')->store('products', 'public'),
                ]);
            }

            $this->syncImages($product, $request);
            $this->syncVariants($product, $data['variants'] ?? []);
            $this->syncBenefits($product, $data['benefits'] ?? []);
            $this->syncFaqs($product, $data['faqs'] ?? []);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Product created successfully.',
                'data' => $product->load(['category', 'brand', 'images', 'variants', 'benefits', 'faqs']),
            ], 201);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Could not create product.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display single product
     */
    public function show(Request $request, $id)
    {
        $product = Product::with(['category', 'brand', 'images', 'variants', 'benefits', 'faqs'])->find($id);

        if (! $product || ! $this->canAccess($product, $request->user('sanctum'))) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $product,
        ]);
    }

    /**
     * Update product
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (! $product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found.',
            ], 404);
        }

        if ($this->isScopedAdmin($request->user()) && $product->owner_id !== $request->user()->id) {
            return response()->json([
                'status' => false,
                'message' => 'You do not have permission to edit this product.',
            ], 403);
        }

        $validator = Validator::make($request->all(), $this->rules($product->id, sometimes: true));

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        DB::beginTransaction();

        try {
            $product->update($this->corefields($data));

            if ($request->hasFile('thumbnail')) {
                if ($product->thumbnail) {
                    Storage::disk('public')->delete($product->thumbnail);
                }

                $product->update([
                    'thumbnail' => $request->file('thumbnail')->store('products', 'public'),
                ]);
            }

            $this->syncImages($product, $request);

            if ($request->has('variants')) {
                $this->syncVariants($product, $data['variants'] ?? []);
            }

            if ($request->has('benefits')) {
                $this->syncBenefits($product, $data['benefits'] ?? []);
            }

            if ($request->has('faqs')) {
                $this->syncFaqs($product, $data['faqs'] ?? []);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Product updated successfully.',
                'data' => $product->load(['category', 'brand', 'images', 'variants', 'benefits', 'faqs']),
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Could not update product.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete product
     */
    public function destroy(Request $request, $id)
    {
        $product = Product::with('images')->find($id);

        if (! $product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found.',
            ], 404);
        }

        if ($this->isScopedAdmin($request->user()) && $product->owner_id !== $request->user()->id) {
            return response()->json([
                'status' => false,
                'message' => 'You do not have permission to delete this product.',
            ], 403);
        }

        if ($product->thumbnail) {
            Storage::disk('public')->delete($product->thumbnail);
        }

        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image);
        }

        // Child rows (images, variants, benefits, faqs, reviews) cascade-delete at the DB level.
        $product->delete();

        return response()->json([
            'status' => true,
            'message' => 'Product deleted successfully.',
        ]);
    }

    /**
     * Validation rules shared by store/update.
     */
    private function rules(?int $productId = null, bool $sometimes = false): array
    {
        $required = $sometimes ? 'sometimes|required' : 'required';

        return [
            'name' => "{$required}|string|max:255",
            'slug' => ['nullable', 'string', 'max:255', 'unique:products,slug,' . $productId],
            'sku' => ['nullable', 'string', 'max:255', 'unique:products,sku,' . $productId],
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'ingredients' => 'nullable|string',
            'who_can_use' => 'nullable|string',
            'how_to_use' => 'nullable|string',
            'product_features' => 'nullable|string',
            'regular_price' => "{$required}|numeric|min:0",
            'sale_price' => 'nullable|numeric|min:0|lte:regular_price',
            'thumbnail' => 'nullable|image|max:2048',
            'is_featured' => 'nullable|boolean',
            'is_best_seller' => 'nullable|boolean',
            'is_new' => 'nullable|boolean',
            'status' => "{$required}|in:active,inactive",
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string',
            'meta_description' => 'nullable|string',

            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
            'primary_image_index' => 'nullable|integer|min:0',
            'existing_image_ids' => 'nullable|array',
            'existing_image_ids.*' => 'integer|exists:product_images,id',
            'primary_image_id' => 'nullable|integer|exists:product_images,id',

            'variants' => 'nullable|array',
            'variants.*.unit' => 'required_with:variants|string|max:255',
            'variants.*.regular_price' => 'required_with:variants|numeric|min:0',
            'variants.*.sale_price' => 'nullable|numeric|min:0',
            'variants.*.stock' => 'required_with:variants|integer|min:0',
            'variants.*.sku' => 'nullable|string|max:255',
            'variants.*.status' => 'nullable|boolean',

            'benefits' => 'nullable|array',
            'benefits.*' => 'string|max:500',

            'faqs' => 'nullable|array',
            'faqs.*.question' => 'required_with:faqs|string|max:500',
            'faqs.*.answer' => 'required_with:faqs|string',
        ];
    }

    /**
     * Keep only the plain product-table columns from validated data.
     */
    private function corefields(array $data): array
    {
        return array_intersect_key($data, array_flip([
            'name', 'slug', 'sku', 'category_id', 'brand_id',
            'short_description', 'description', 'ingredients', 'who_can_use',
            'how_to_use', 'product_features', 'regular_price', 'sale_price',
            'is_featured', 'is_best_seller', 'is_new', 'status',
            'meta_title', 'meta_keywords', 'meta_description',
        ]));
    }

    private function syncImages(Product $product, Request $request): void
    {
        if ($request->has('existing_image_ids')) {
            $keepIds = $request->input('existing_image_ids', []);

            $product->images()
                ->whereNotIn('id', $keepIds)
                ->get()
                ->each(function ($image) {
                    Storage::disk('public')->delete($image->image);
                    $image->delete();
                });
        }

        if ($request->filled('primary_image_id')) {
            $product->images()->update(['is_primary' => false]);
            $product->images()->where('id', $request->primary_image_id)->update(['is_primary' => true]);
        }

        if ($request->hasFile('images')) {
            $nextSort = (int) $product->images()->max('sort_order') + 1;
            $hasPrimary = $product->images()->where('is_primary', true)->exists();

            foreach ($request->file('images') as $index => $file) {
                $product->images()->create([
                    'image' => $file->store('products', 'public'),
                    'is_primary' => ! $hasPrimary && $index === (int) $request->integer('primary_image_index', 0),
                    'sort_order' => $nextSort++,
                ]);

                $hasPrimary = true;
            }
        }
    }

    private function syncVariants(Product $product, array $variants): void
    {
        $product->variants()->delete();

        foreach ($variants as $variant) {
            $product->variants()->create([
                'unit' => $variant['unit'],
                'regular_price' => $variant['regular_price'],
                'sale_price' => $variant['sale_price'] ?? null,
                'stock' => $variant['stock'],
                'sku' => $variant['sku'] ?? null,
                'status' => $variant['status'] ?? true,
            ]);
        }
    }

    private function syncBenefits(Product $product, array $benefits): void
    {
        $product->benefits()->delete();

        foreach ($benefits as $index => $benefit) {
            if ($benefit === '' || $benefit === null) {
                continue;
            }

            $product->benefits()->create([
                'benefit' => $benefit,
                'sort_order' => $index,
            ]);
        }
    }

    private function syncFaqs(Product $product, array $faqs): void
    {
        $product->faqs()->delete();

        foreach ($faqs as $index => $faq) {
            $product->faqs()->create([
                'question' => $faq['question'],
                'answer' => $faq['answer'],
                'sort_order' => $index,
            ]);
        }
    }
}
