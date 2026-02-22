<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use App\Traits\HandlesGcsImage;

class Setting extends Model implements TranslatableContract
{
    use Translatable, HandlesGcsImage;


    public $translatedAttributes = ['site_name', 'site_description', 'copyright'];


    protected $fillable = ['site_email', 'site_logo', 'site_phone', 'maintenance_mode'];

    protected $appends = ['site_logo_url'];

    public function getSiteLogoUrlAttribute()
    {
        return $this->site_logo ? $this->getImageUrl($this->site_logo) : null;
    }
}
