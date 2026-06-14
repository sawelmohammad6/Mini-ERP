<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;

/**
 * Why Form Request?
 * -----------------
 * See StoreUserRequest — same benefits apply for updates.
 * Allows conditional password rule (nullable for edits).
 */
class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->route('user')),
            ],
            'role'     => ['required', new Enum(UserRole::class)],
            'password' => ['nullable', 'string', Password::min(6)],
        ];
    }
}
