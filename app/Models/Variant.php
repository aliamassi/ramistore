<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Variant extends Model
{
    use HasTranslations;

//    public array $translatable = ['name'];
    protected $fillable = ['product_id', 'name', 'price'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

}
