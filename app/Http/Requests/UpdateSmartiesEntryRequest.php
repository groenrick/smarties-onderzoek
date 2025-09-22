<?php

declare(strict_types=1);

// app/Http/Requests/UpdateSmartiesEntryRequest.php
namespace App\Http\Requests;

class UpdateSmartiesEntryRequest extends StoreSmartiesEntryRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'date' => ['sometimes', 'date', 'date_format:Y-m-d', 'before_or_equal:today'],
            'contributor' => ['sometimes', 'string', 'max:255'],
            'red' => ['sometimes', 'integer', 'min:0', 'max:50'],
            'orange' => ['sometimes', 'integer', 'min:0', 'max:50'],
            'yellow' => ['sometimes', 'integer', 'min:0', 'max:50'],
            'green' => ['sometimes', 'integer', 'min:0', 'max:50'],
            'blue' => ['sometimes', 'integer', 'min:0', 'max:50'],
            'pink' => ['sometimes', 'integer', 'min:0', 'max:50'],
            'purple' => ['sometimes', 'integer', 'min:0', 'max:50'],
            'brown' => ['sometimes', 'integer', 'min:0', 'max:50'],
        ]);
    }
}
