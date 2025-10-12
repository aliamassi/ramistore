<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'     => $this->id,
            'name'   => $this->name,
            'images' => $this->getMedia('products')->map(function ($m) {
                return [
                    'id'      => $m->id,
                    'url'     => $m->getFullUrl(),          // absolute URL
                    'thumb'   => $m->hasGeneratedConversion('thumb') ? $m->getFullUrl('thumb') : null,
                    'mime'    => $m->mime_type,
                    'size'    => $m->size,
                    'is_main' => (bool) $m->getCustomProperty('is_main'),
                ];
            }),
        ];
    }
}
