<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class WishlistController extends Controller
{
    // Toggle function (Add/Remove from wishlist)
    public function toggle(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        $user = $request->user();

        // The toggle method adds the product if it doesn't exist, and removes it if it does
        $user->wishlists()->toggle($request->product_id);

        return response()->json([
            'success' => true,
            // Return an array of wishlist product IDs for easy handling in JavaScript
            'wishlist_ids' => $user->wishlists()->pluck('products.id')
        ]);
    }

    // Fetch wishlist IDs when the page loads
    public function getIds(Request $request)
    {
        return response()->json([
            'success' => true,
            'wishlist_ids' => $request->user()->wishlists()->pluck('products.id')
        ]);
    }

    // 1. Fetch wishlist products for the authenticated customer (from database)
    public function index(Request $request)
    {
        $products = $request->user()->wishlists;

        // 2. Use ProductResource::collection to wrap the product data
        return response()->json([
            'success' => true,
            'products' => ProductResource::collection($products)
        ]);
    }

    // Fetch wishlist products for guests based on provided IDs
    public function guestWishlist(Request $request)
    {
        $ids = $request->ids ?? [];

        if (empty($ids)) {
            return response()->json(['success' => true, 'products' => []]);
        }

        $products = Product::whereIn('id', $ids)->get();

        // 3. Wrap product data for guest visitors as well
        return response()->json([
            'success' => true,
            'products' => ProductResource::collection($products)
        ]);
    }
}
