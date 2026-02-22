<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Rely on the locale set by the Middleware
        $locale = app()->getLocale();

        return [
            'id'               => $this->id,
            'slug'             => $this->slug, // Slug is static (not translated based on current database design)
            'title'            => $this->translateOrNew($locale)->title,
            'content'          => $this->translateOrNew($locale)->content,

            // SEO data is crucial for pages
            'meta_title'       => $this->translateOrNew($locale)->meta_title,
            'meta_description' => $this->translateOrNew($locale)->meta_description,
            'meta_keywords'    => $this->translateOrNew($locale)->meta_keywords,

            'created_at'       => $this->created_at->format('Y-m-d'),
        ];
    }
}
