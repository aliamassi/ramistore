<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Constant extends Model
{
    protected $fillable = [
        'parent_id',
        'name',
        'key',
        'value',
        'extra',
    ];


    public static function getConstants($key)
    {
        return self::where('key', $key)->whereNull('parent_id')->with('children')->first();
    }

    public function children()
    {
        return $this->hasMany(Constant::class, 'parent_id', 'id');
    }


}
