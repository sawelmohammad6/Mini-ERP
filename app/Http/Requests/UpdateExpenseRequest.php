<?php

namespace App\Http\Requests;

use App\Enums\ExpenseCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount'   => ['required', 'numeric', 'min:0'],
            'category' => ['required', new Enum(ExpenseCategory::class)],
            'note'     => ['nullable', 'string'],
            'date'     => ['required', 'date'],
        ];
    }
}
