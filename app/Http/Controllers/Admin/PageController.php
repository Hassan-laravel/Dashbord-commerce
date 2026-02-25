<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::latest()->get();
        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        // Validate fields based on the current locale
        $locale = app()->getLocale();

        $request->validate([
            "$locale.title" => 'required|string|max:255',
            "$locale.content" => 'required',
        ]);

        $data = $request->except(['_token']);

        // If the user manually edited the slug, use it; otherwise, generate it from the title
        $slugInput = $request->input('slug') ?: $request->input("$locale.title");

        // Replace spaces with hyphens
        $slug = str_replace(' ', '-', $slugInput);

        // This regex keeps only Arabic/English letters, numbers, and hyphens
        $data['slug'] = preg_replace('/[^\p{L}\p{N}\-_]+/u', '', $slug);

        // Status handling
        $data['status'] = $request->has('status') ? 1 : 0;

        Page::create($data);

        return redirect()->route('admin.pages.index')->with('success', __('messages.created_successfully'));
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $locale = app()->getLocale();

        $request->validate([
            "$locale.title" => 'required|string|max:255',
        ]);

        $data = $request->except(['_token', '_method']);
        $data['status'] = $request->has('status') ? 1 : 0;

        // Update the Slug only if we are in the default language or as needed
        // $data['slug'] = Str::slug($request->input("$locale.title"));

        $page->update($data);

        return redirect()->route('admin.pages.index')->with('success', __('messages.updated_successfully'));
    }

    public function destroy(Page $page)
    {
        $page->delete();
        return back()->with('success', __('messages.deleted_successfully'));
    }
}
