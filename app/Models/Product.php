<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Product extends Model implements HasMedia
{
    use HasTranslations;
    use HasFactory;
    use InteractsWithMedia;

//    public array $translatable = ['name', 'description'];
    protected $fillable = ['category_id', 'name', 'description', 'price', 'type', 'is_visible'];


    // Optional: Append translated attribute to JSON
    protected $appends = ['image'];

    // Method 1: Using accessor to always return current locale
    public function getImageAttribute()
    {
        if ($this->getMedia('products')->first()) {
            return $this->getMedia('products')->first()->getFullUrl();
        }
        return null;
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(Variant::class, 'product_id');
    }
//    public function getTranslatedDescriptionAttribute()
//    {
//        return $this->getTranslation('description', app()->getLocale());
//    }


}
