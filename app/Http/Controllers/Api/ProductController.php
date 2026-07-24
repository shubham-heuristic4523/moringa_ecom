<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display all products
     */
    public function index()
    {
        $products = Product::latest()->paginate(10);

        return response()->json([
            'status' => true,
            'message' => 'Products fetched successfully.',
            'data' => $products
        ]);
    }

    /**
     * Store a new product
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer|min:0'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ],422);
        }

        $product = Product::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Product created successfully.',
            'data' => $product
        ],201);
    }

    /**
     * Display single product
     */
    public function show($id)
    {
        $product = Product::find($id);

        if(!$product){
            return response()->json([
                'status' => false,
                'message' => 'Product not found.'
            ],404);
        }

        return response()->json([
            'status' => true,
            'data' => $product
        ]);
    }

    /**
     * Update product
     */
    public function update(Request $request,$id)
    {
        $product = Product::find($id);

        if(!$product){
            return response()->json([
                'status' => false,
                'message' => 'Product not found.'
            ],404);
        }

        $validator = Validator::make($request->all(),[
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric',
            'quantity' => 'sometimes|required|integer|min:0'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ],422);
        }

        $product->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Product updated successfully.',
            'data' => $product
        ]);
    }

    /**
     * Delete product
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if(!$product){
            return response()->json([
                'status' => false,
                'message' => 'Product not found.'
            ],404);
        }

        $product->delete();

        return response()->json([
            'status' => true,
            'message' => 'Product deleted successfully.'
        ]);
    }
}