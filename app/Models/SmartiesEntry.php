<?php

declare(strict_types=1);

// app/Models/SmartiesEntry.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmartiesEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'contributor',
        'red',
        'orange',
        'yellow',
        'green',
        'blue',
        'pink',
        'purple',
        'brown',
        'total'
    ];

    protected $casts = [
        'date' => 'date',
        'red' => 'integer',
        'orange' => 'integer',
        'yellow' => 'integer',
        'green' => 'integer',
        'blue' => 'integer',
        'pink' => 'integer',
        'purple' => 'integer',
        'brown' => 'integer',
        'total' => 'integer'
    ];

    protected $attributes = [
        'red' => 0,
        'orange' => 0,
        'yellow' => 0,
        'green' => 0,
        'blue' => 0,
        'pink' => 0,
        'purple' => 0,
        'brown' => 0,
        'total' => 0
    ];
}
