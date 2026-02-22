<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use App\Traits\HandlesGcsImage;

class Product extends Model implements TranslatableContract
{
    use Translatable, HandlesGcsImage;

    // Attributes that are translatable
    public $translatedAttributes = [
        'name', 'slug', 'short_description', 'description',
        'meta_title', 'meta_description'
    ];

    // Main attributes that are mass-assignable
    protected $fillable = [
        'price', 'discount_price', 'quantity', 'sku', 'main_image', 'status'
    ];

    /**
     * Many-to-Many relationship with categories
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    // expose url attributes
    protected $appends = ['main_image_url'];

    public function getMainImageUrlAttribute()
    {
        return $this->main_image ? $this->getImageUrl($this->main_image) : null;
    }

    /**
     * Helper accessor to iterate gallery urls in a view or resource
     */
    public function getGalleryUrlsAttribute()
    {
        return $this->images->map(fn($img) => $img->image_url);
    }

    /**
     * One-to-Many relationship with product gallery images
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Relationship with product reviews, ordered by latest
     */
    public function reviews()
    {
        return $this->hasMany(Review::class)->latest();
    }
}
