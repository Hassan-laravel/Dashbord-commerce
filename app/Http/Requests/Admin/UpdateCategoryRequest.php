<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules(): array
    {
        $category = $this->route('category');
        $id = $category instanceof \Illuminate\Database\Eloquent\Model ? $category->id : $category;
        $locale = app()->getLocale();

        return [
            'status' => 'required|in:0,1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',

            "$locale.name" => 'required|string|max:255',
            "$locale.slug" => "nullable|string|max:255|unique:category_translations,slug,{$id},category_id",
            "$locale.meta_title" => 'nullable|string|max:255',
            "$locale.meta_description" => 'nullable|string',
        ];
    }
}
