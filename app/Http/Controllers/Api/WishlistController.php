<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WishlistController extends Controller
{
   
    public function index()
    {
        $wishlist = Wishlist::with(['user', 'product'])->latest()->paginate(10);

        return response()->json([
            'status' => true,
            'message' => 'Wishlist fetched successfully.',
            'data' => $wishlist
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ],422);
        }

        $exists = Wishlist::where('user_id',$request->user_id)
                    ->where('product_id',$request->product_id)
                    ->first();

        if($exists){
            return response()->json([
                'status' => false,
                'message' => 'Product already exists in wishlist.'
            ],409);
        }

        $wishlist = Wishlist::create($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Product added to wishlist.',
            'data' => $wishlist
        ],201);
    }

    public function show($id)
    {
        $wishlist = Wishlist::with(['user','product'])->find($id);

        if(!$wishlist){
            return response()->json([
                'status' => false,
                'message' => 'Wishlist item not found.'
            ],404);
        }

        return response()->json([
            'status' => true,
            'data' => $wishlist
        ]);
    }

    public function update(Request $request, $id)
    {
        $wishlist = Wishlist::find($id);

        if(!$wishlist){
            return response()->json([
                'status' => false,
                'message' => 'Wishlist item not found.'
            ],404);
        }

        $validator = Validator::make($request->all(),[
            'user_id' => 'sometimes|required|exists:users,id',
            'product_id' => 'sometimes|required|exists:products,id',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ],422);
        }

        $wishlist->update($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Wishlist updated successfully.',
            'data' => $wishlist
        ]);
    }

    /**
     * Remove from wishlist
     */
    public function destroy($id)
    {
        $wishlist = Wishlist::find($id);

        if(!$wishlist){
            return response()->json([
                'status' => false,
                'message' => 'Wishlist item not found.'
            ],404);
        }

        $wishlist->delete();

        return response()->json([
            'status' => true,
            'message' => 'Product removed from wishlist.'
        ]);
    }
}