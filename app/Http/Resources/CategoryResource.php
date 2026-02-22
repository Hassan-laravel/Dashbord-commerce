<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $locale = app()->getLocale();

        return [
            'id'    => $this->id,
            'name'  => $this->translateOrNew($locale)->name,
            'slug'  => $this->translateOrNew($locale)->slug,
            'image' => $this->image_url,
        ];
    }
}
