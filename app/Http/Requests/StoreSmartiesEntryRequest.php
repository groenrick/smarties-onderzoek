<?php

declare(strict_types=1);

// app/Http/Requests/StoreSmartiesEntryRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSmartiesEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => ['required', 'date', 'date_format:Y-m-d', 'before_or_equal:today'],
            'contributor' => ['required', 'string', 'max:255'],
            'red' => ['required', 'integer', 'min:0', 'max:50'],
            'orange' => ['required', 'integer', 'min:0', 'max:50'],
            'yellow' => ['required', 'integer', 'min:0', 'max:50'],
            'green' => ['required', 'integer', 'min:0', 'max:50'],
            'blue' => ['required', 'integer', 'min:0', 'max:50'],
            'pink' => ['required', 'integer', 'min:0', 'max:50'],
            'purple' => ['required', 'integer', 'min:0', 'max:50'],
            'brown' => ['required', 'integer', 'min:0', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'date.required' => 'The counting date is required.',
            'date.before_or_equal' => 'The date cannot be in the future.',
            'contributor.required' => 'The contributor name is required.',
            '*.integer' => 'Color counts must be whole numbers.',
            '*.min' => 'Color counts cannot be negative.',
            '*.max' => 'Color counts seem too high for a standard Smarties box.',
        ];
    }
}
