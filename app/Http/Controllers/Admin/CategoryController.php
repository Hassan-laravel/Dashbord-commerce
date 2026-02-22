<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Traits\HandlesGcsImage;

class CategoryController extends Controller
{
    use HandlesGcsImage;
// public static function middleware(): array
//     {
//         return [
//             // 1. Protect modification operations (create, store, edit, update, destroy)
//             // Use 'except' to exclude the index method
//             new Middleware('permission:manage-categories', except: ['index']),

//             // 2. Protect the index method only
//             // Allow access if the user has 'view' OR 'manage' permission
//             // The | symbol represents OR
//             new Middleware('permission:view-categories|manage-categories', only: ['index']),
//         ];
//     }

    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function store(StoreCategoryRequest $request)
    {
        $data = $request->all();

        // Handle image upload via GCS
        if ($request->hasFile('image')) {
            $upload = $this->uploadImageToGcs($request->file('image'), 'categories');
            $data['image'] = $upload['path'] ?? null;
        }

        Category::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', __('dashboard.messages.category_created'));
    }

    public function edit($id)
    {
        $category = Category::with('translations')->findOrFail($id);

        $response = [
            'id' => $category->id,
            'status' => $category->status,
            // Send image URL to be displayed in the modal
            'image_url' => $category->image_url,
        ];

        foreach ($category->translations as $translation) {
            $response[$translation->locale] = [
                'name' => $translation->name,
                'slug' => $translation->slug,
                'meta_title' => $translation->meta_title,
                'meta_description' => $translation->meta_description,
            ];
        }

        return response()->json($response);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $data = $request->all();

        // Handle image update
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($category->image) {
                $this->deleteImageFromGcs($category->image);
            }
            $upload = $this->uploadImageToGcs($request->file('image'), 'categories');
            $data['image'] = $upload['path'] ?? null;
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', __('dashboard.messages.category_updated'));
    }

    public function destroy(Category $category)
    {
        // Delete the image when the category is deleted
        if ($category->image) {
            $this->deleteImageFromGcs($category->image);
        }

        $category->delete();
        return back()->with('success', __('dashboard.messages.category_deleted'));
    }
}
