<?php

declare(strict_types=1);

// app/Http/Resources/SmartiesEntryResource.php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SmartiesEntryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date->format('Y-m-d'),
            'contributor' => $this->contributor,
            'colors' => [
                'red' => $this->red,
                'orange' => $this->orange,
                'yellow' => $this->yellow,
                'green' => $this->green,
                'blue' => $this->blue,
                'pink' => $this->pink,
                'purple' => $this->purple,
                'brown' => $this->brown,
            ],
            'total' => $this->total,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
