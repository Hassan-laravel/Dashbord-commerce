<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use App\Models\CategoryTranslation; // Ensure the class is imported
use App\Traits\HandlesGcsImage;

class Category extends Model implements TranslatableContract
{
    use Translatable, HandlesGcsImage;

    // 1. Manually define the translation model class to prevent errors
    public $translationModel = CategoryTranslation::class;

    // 2. Attributes that are translatable
    public $translatedAttributes = ['name', 'slug', 'meta_title', 'meta_description'];

    // 3. Main attributes (Guarded is used to allow language arrays to pass through)
    protected $guarded = ['id'];

    // 4. Always eager load translations
    protected $with = ['translations'];

    // Automatically include the computed URL when serializing
    protected $appends = ['image_url'];

    /**
     * Accessor for the public URL of the stored image.
     */
    public function getImageUrlAttribute()
    {
        return $this->image ? $this->getImageUrl($this->image) : null;
    }

    /**
     * Relationship with products (Many-to-Many)
     */
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
