<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HandlesGcsImage;

class ProductImage extends Model
{
    use HandlesGcsImage;

    protected $fillable = ['product_id', 'image_path', 'sort_order'];

    // append computed url
    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return $this->image_path ? $this->getImageUrl($this->image_path) : null;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
