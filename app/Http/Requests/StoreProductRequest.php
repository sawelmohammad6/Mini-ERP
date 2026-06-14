<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'            => ['required', 'string', 'max:255'],
            'price'           => ['required', 'numeric', 'min:0'],
            'description'     => ['nullable', 'string'],
            'image'           => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'stock_quantity'  => ['required', 'integer', 'min:0'],
            'low_stock_alert' => ['required', 'integer', 'min:1'],
        ];
    }
}
