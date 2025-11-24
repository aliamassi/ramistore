<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Admin extends Authenticatable implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'business_name',
        'currency',
        'email',
        'password',
    ];

    protected $appends = [
        'logo',
        'domain',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getLogoAttribute(){
       return $this->getFirstMediaUrl('logo');
    }
    public function getDomainAttribute(){
        $business_name = $this->settings()->where('key','=','business_name')->first();

      if($business_name) return request()->getSchemeAndHttpHost()."/restaurant/$business_name->value";
       return request()->getSchemeAndHttpHost();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile();
        // singleFile() ensures adding a new file deletes the previous one
    }

    public function categories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Category::class,'admin_id');
    }
    public function settings(): \Illuminate\Database\Eloquent\Relations\HasMany|Setting
    {
        return $this->hasMany(Setting::class,'admin_id');
    }

    public function setSettings($inputs)
    {
        foreach ($inputs as $key => $value) {
            if(is_array($value)){
                $value = json_encode($value);
            }

            $this->settings()->updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
