<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // DEF-002 FIX: Remove 'exists:users,username' to prevent user enumeration.
            // Username existence is checked in the controller — all auth failures return 401.
            'username' => 'required|string',
            'password' => 'required|string|min:6',
        ];
    }

        public function messages(): array
    {
        return [
            'username.required' => 'Username wajib diisi',
            'username.string' => 'Username harus berupa teks',
            'password.required' => 'Password wajib diisi',
            'password.string' => 'Password harus berupa teks',
            'password.min' => 'Password minimal 6 karakter',
        ];
    }
}
