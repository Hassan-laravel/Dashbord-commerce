<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Removed header reading code from here
        // Removed app()->setLocale(...)

        // Now relying on the locale determined by the Middleware previously
        $locale = app()->getLocale();

        return [
            'id'                => $this->id,
            'name'              => $this->translateOrNew($locale)->name,
            'slug'              => $this->translateOrNew($locale)->slug,
            'short_description' => $this->translateOrNew($locale)->short_description,
            'description'       => $this->translateOrNew($locale)->description,
            'price'             => (float) $this->price,
            'discount_price'    => (float) $this->discount_price,
            'has_discount'      => $this->discount_price > 0,
            'quantity'          => (int) $this->quantity,
            'main_image'        => $this->main_image_url,

            // Gallery images
            'gallery'           => $this->images->map(function ($img) {
                return $img->image_url;
            }),

            // Relationship with categories (ensure CategoryResource is also clean)
            'categories'        => CategoryResource::collection($this->whenLoaded('categories')),

            'sku'               => $this->sku,

            // Reviews section:
            'reviews' => $this->whenLoaded('reviews', function () {
                return $this->reviews->map(function ($review) {
                    return [
                        'id' => $review->id,
                        'rating' => $review->rating,
                        'comment' => $review->comment,
                        'created_at' => $review->created_at,
                        'user' => [
                            'name' => $review->user->name ?? 'User',
                        ],
                    ];
                });
            }),
        ];
    }
}
