<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'admin_id','name', 'key', 'value',
    ];

    public static function setSettings($inputs)
    {
        foreach ($inputs as $key => $value) {
            self::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }

    public static function getSettings($array)
    {
        $data = self::whereIn('key', $array)->pluck('value', 'key')->all();
        $output = array_fill_keys($array, "");
        return array_merge($output, $data);
    }

    public static function getSetting($key, $default = null)
    {
        $pair = self::where('key', $key)->pluck('value', 'key')->first();
        if ($pair) return $pair;
        return $default;
    }

}
