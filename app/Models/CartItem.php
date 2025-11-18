<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{

    protected $fillable = [
        'cart_id',
        'product_id',
        'customization_option_id',
        'quantity',
        'price',
        'customization_name',
        'customization_detail'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer'
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function customizationOption()
    {
        return $this->belongsTo(CustomizationOption::class);
    }

    public function getSubtotalAttribute()
    {
        return $this->price * $this->quantity;
    }
}
