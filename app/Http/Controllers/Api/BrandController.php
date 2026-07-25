<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    /**
     * Display all brands
     */
    public function index()
    {
        $brands = Brand::latest()->paginate(10);

        return response()->json([
            'status' => true,
            'message' => 'Brands fetched successfully.',
            'data' => $brands
        ]);
    }

    /**
     * Store brand
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'name' => 'required|string|max:255',
            'description' => 'nullable|string',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $brand = Brand::create($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Brand created successfully.',
            'data' => $brand
        ], 201);
    }

    /**
     * Display single brand
     */
    public function show($id)
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json([
                'status' => false,
                'message' => 'Brand not found.'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $brand
        ]);
    }

    /**
     * Update brand
     */
    public function update(Request $request, $id)
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json([
                'status' => false,
                'message' => 'Brand not found.'
            ], 404);
        }

        $validator = Validator::make($request->all(), [

            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $brand->update($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Brand updated successfully.',
            'data' => $brand
        ]);
    }

    /**
     * Delete brand
     */
    public function destroy($id)
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json([
                'status' => false,
                'message' => 'Brand not found.'
            ], 404);
        }

        $brand->delete();

        return response()->json([
            'status' => true,
            'message' => 'Brand deleted successfully.'
        ]);
    }
}