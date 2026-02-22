<?php

use App\Http\Controllers\Api\CheckoutController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FrontendController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\WishlistController;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\GcsTestController;

// Note: The default prefix for this file is already /api
Route::post('/test-gcs-upload', [GcsTestController::class, 'uploadTest']);
Route::get('/test-gcs-connection', [GcsTestController::class, 'testConnection']);
Route::group(['prefix' => 'v1'], function () {

    // Public Frontend Endpoints
    Route::get('/settings', [FrontendController::class, 'settings']);
    Route::get('/categories', [FrontendController::class, 'categories']);
    Route::get('/products/latest', [FrontendController::class, 'latestProducts']);
    Route::get('/products/category/{id}', [FrontendController::class, 'categoryProducts']);
    Route::get('/product/{slug}', [FrontendController::class, 'productDetails']);
    Route::get('/page/{slug}', [FrontendController::class, 'page']);
    Route::get('/pages', [FrontendController::class, 'pages']);
    Route::get('/search', [FrontendController::class, 'search']);

    // Checkout & Orders
    Route::post('/checkout', [CheckoutController::class, 'store']);

    // Authentication Routes (Public)
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Guest Wishlist management
    Route::post('/wishlist/guest', [WishlistController::class, 'guestWishlist']);

    // Authenticated Customer Routes (Protected by Sanctum)
    Route::middleware('auth:sanctum')->group(function () {

        // User Profile Management
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);

        // Product Reviews
        Route::post('/products/{product}/reviews', [ReviewController::class, 'store']);

        // Authenticated Wishlist
        Route::get('/wishlist', [WishlistController::class, 'index']);
        Route::post('/wishlist/toggle', [WishlistController::class, 'toggle']);
        Route::get('/wishlist/ids', [WishlistController::class, 'getIds']);

        // Session Management
        Route::post('/logout', [AuthController::class, 'logout']);

        // Order History
        Route::get('/my-orders', function (Request $request) {
            $orders = Order::where('user_id', $request->user()->id)
                ->with('items') // Eager load order items
                ->latest()
                ->get();
            return response()->json(['success' => true, 'orders' => $orders]);
        });
    });
});

// External Webhooks
Route::post('/webhook/stripe', [CheckoutController::class, 'handleStripeWebhook']);
