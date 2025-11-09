<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasTranslations;
    use HasFactory;

//    public array $translatable = ['name'];

    protected $fillable = ['admin_id','name','is_visible'];

    public function products(){
        return $this->hasMany(Product::class,'category_id');
    }
}
