<?php

declare(strict_types=1);

// app/DTOs/SmartiesEntryData.php
namespace App\DTOs;

use App\Models\SmartiesEntry;
use Carbon\Carbon;

class SmartiesEntryData
{
    public function __construct(
        public readonly int $id,
        public readonly Carbon $date,
        public readonly string $contributor,
        public readonly int $red,
        public readonly int $orange,
        public readonly int $yellow,
        public readonly int $green,
        public readonly int $blue,
        public readonly int $pink,
        public readonly int $purple,
        public readonly int $brown,
        public readonly int $total,
        public readonly Carbon $created_at,
        public readonly Carbon $updated_at
    ) {}

    public static function fromModel(SmartiesEntry $model): self
    {
        return new self(
            id: $model->id,
            date: $model->date,
            contributor: $model->contributor,
            red: $model->red,
            orange: $model->orange,
            yellow: $model->yellow,
            green: $model->green,
            blue: $model->blue,
            pink: $model->pink,
            purple: $model->purple,
            brown: $model->brown,
            total: $model->total,
            created_at: $model->created_at,
            updated_at: $model->updated_at
        );
    }

    public function toArray(): array
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
