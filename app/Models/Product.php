<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Product extends Model implements HasMedia
{
    use HasTranslations;
    use HasFactory;
    use InteractsWithMedia;

//    public array $translatable = ['name', 'description'];
    protected $fillable = ['category_id', 'name', 'description', 'price', 'type'];

    // Optional: Append translated attribute to JSON
//    protected $appends = ['translated_name','translated_description'];

    // Method 1: Using accessor to always return current locale
//    public function getTranslatedNameAttribute()
//    {
//        return $this->getTranslation('name', app()->getLocale());
//    }
//    public function getTranslatedDescriptionAttribute()
//    {
//        return $this->getTranslation('description', app()->getLocale());
//    }


}
