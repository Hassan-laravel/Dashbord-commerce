<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Traits\HandlesGcsImage;

class ProductController extends Controller implements HasMiddleware
{
    use HandlesGcsImage;
    public static function middleware(): array
    {
        return [
            new Middleware('permission:manage-products', except: ['index']),
            new Middleware('permission:view-products|manage-products', only: ['index']),
        ];
    }

    public function index()
    {
        // Fetch products with their associated categories and translations for the current locale
        $products = Product::with('categories')->latest()->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // 1. Data Validation (for the current locale only)
        $locale = app()->getLocale();
        $request->validate([
            "$locale.name" => 'required|max:255',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'categories' => 'required|array', // Selected categories array
            'main_image' => 'required|image|max:2048',
            'images.*' => 'nullable|image|max:2048', // Additional gallery images
        ]);

        // 2. Process basic product data
        $data = $request->except(['main_image', 'images', 'categories', 'status']);
        $data['status'] = $request->has('status') ? 1 : 0;

        // Automatically generate slug from name if not provided
        $data[$locale]['slug'] = Str::slug($request->input("$locale.name"));

        // 3. Upload main image to GCS
        if ($request->hasFile('main_image')) {
            $upload = $this->uploadImageToGcs($request->file('main_image'), 'products/main');
            $data['main_image'] = $upload['path'] ?? null;
        }

        // 4. Save product (automatic handling of current translation)
        $product = Product::create($data);

        // 5. Attach categories (Many-to-Many)
        $product->categories()->attach($request->categories);

        // 6. Upload gallery images (Infinite Gallery) to GCS
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $upload = $this->uploadImageToGcs($image, 'products/gallery');
                if ($upload) {
                    $product->images()->create(['image_path' => $upload['path']]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', __('dashboard.general.created_successfully'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        // Get associated category IDs to display them as selected
        $selectedCategories = $product->categories->pluck('id')->toArray();
        return view('admin.products.edit', compact('product', 'categories', 'selectedCategories'));
    }

    public function update(Request $request, Product $product)
    {
        $locale = app()->getLocale();
        // Validation (fields are optional here as we might be in "complete translation" mode for another language)
        $request->validate([
            "$locale.name" => 'required|max:255',
            'price' => 'required|numeric',
            'categories' => 'required|array',
        ]);

        $data = $request->except(['main_image', 'images', 'categories', 'status']);
        $data['status'] = $request->has('status') ? 1 : 0;

        // Update Slug
        $data[$locale]['slug'] = Str::slug($request->input("$locale.name"));

        // Update main image
        if ($request->hasFile('main_image')) {
            if ($product->main_image) {
                $this->deleteImageFromGcs($product->main_image);
            }
            $upload = $this->uploadImageToGcs($request->file('main_image'), 'products/main');
            $data['main_image'] = $upload['path'] ?? null;
        }

        $product->update($data);

        // Update category relationships (Sync removes old and adds new)
        $product->categories()->sync($request->categories);

        // Add new images to the gallery
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $upload = $this->uploadImageToGcs($image, 'products/gallery');
                if ($upload) {
                    $product->images()->create(['image_path' => $upload['path']]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', __('dashboard.general.updated_successfully'));
    }

    public function destroy(Product $product)
    {
        // Delete images from storage before deleting records
        if ($product->main_image) {
            $this->deleteImageFromGcs($product->main_image);
        }
        foreach ($product->images as $img) {
            $this->deleteImageFromGcs($img->image_path);
        }

        $product->delete();
        return back()->with('success', __('dashboard.general.deleted_successfully'));
    }

    public function deleteImage($id)
    {
        $image = ProductImage::findOrFail($id);

        // Delete the file from storage
        if ($image->image_path) {
            $this->deleteImageFromGcs($image->image_path);
        }

        $image->delete();

        return response()->json(['success' => __('dashboard.general.image_deleted')]);
    }
}
