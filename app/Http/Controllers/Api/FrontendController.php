<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Product;
use App\Http\Resources\SettingResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Http\Resources\PageResource;

class FrontendController extends Controller
{
    // 1. Fetch general settings (for Header and Footer)
    public function settings()
    {
        $setting = Setting::first();
        return new SettingResource($setting);
    }

    // 2. Fetch category list (for Main Menu)
    public function categories()
    {
        // Ensure you have an 'active' scope in the model or use where('status', 1)
        $categories = Category::where('status', 1)->get();
        return CategoryResource::collection($categories);
    }

    // 3. Fetch latest products (for Home Page)
    public function latestProducts()
    {
        $products = Product::with(['categories', 'images'])
            ->where('status', 1)
            ->latest()
            ->take(8)
            ->get();
        return ProductResource::collection($products);
    }

    // 4. Fetch single product details (Product Page)
    public function productDetails($slug)
    {
        // Searching for a product by a translated slug requires a specific query.
        // For Astrotomic Translatable, we search within the translations table.
        $product = Product::whereTranslation('slug', $slug)
            ->with(['categories', 'images', 'reviews.user']) // Load necessary relationships
            ->firstOrFail();

        return new ProductResource($product);
    }

    // 5. Fetch products by category (Category Page with Filters)
    public function categoryProducts(Request $request, $id)
    {
        // 1. Initialize base query
        $query = Product::where('status', 1)
            ->whereHas('categories', function ($q) use ($id) {
                $q->where('categories.id', $id);
            })
            ->with(['categories', 'images']); // Load necessary relationships

        // 2. Apply Price Filter (Maximum Price)
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // 3. Apply Price Filter (Minimum Price - Optional)
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        // 4. Apply Sorting Logic
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->latest();
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest(); // Default sorting
        }

        // 5. Execute query with pagination
        $products = $query->paginate(12);

        return ProductResource::collection($products);
    }

    // 6. Fetch all active pages
    public function pages()
    {
        $pages = Page::where('status', 1)->get();
        return PageResource::collection($pages);
    }

    // 7. Fetch a specific page by slug
    public function page($slug)
    {
        $page = Page::where('slug', $slug)
            ->where('status', 1) // Ensure page is active
            ->firstOrFail(); // Return 404 if not found

        return new PageResource($page);
    }

    // 8. Search for products
    public function search(Request $request)
    {
        $keyword = $request->query('q');

        // Validate search keyword presence
        if (!$keyword) {
            return response()->json(['data' => []]);
        }

        $products = Product::where('status', 1)
            ->where(function ($query) use ($keyword) {
                // Correct way with Astrotomic: Search within translations table
                $query->whereTranslationLike('name', "%{$keyword}%")
                      ->orWhereTranslationLike('short_description', "%{$keyword}%")
                      ->orWhereTranslationLike('description', "%{$keyword}%");

                // Search by SKU (found in the main table as it is not translated)
                $query->orWhere('sku', 'LIKE', "%{$keyword}%");
            })
            ->with('translations') // Crucial for loading translations with results
            ->latest()
            ->paginate(20);

        return ProductResource::collection($products);
    }
}
