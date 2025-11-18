<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomizationOption extends Model
{

    protected $fillable = [
        'product_id',
        'name',
        'detail',
        'orders_count',
        'price_modifier',
        'is_default',
        'order'
    ];

    protected $casts = [
        'price_modifier' => 'decimal:2',
        'is_default' => 'boolean',
        'orders_count' => 'integer'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getFormattedOrdersAttribute()
    {
        if ($this->orders_count >= 1000) {
            return floor($this->orders_count / 1000) . 'k+ orders';
        }
        return $this->orders_count . ' orders';
    }
}
