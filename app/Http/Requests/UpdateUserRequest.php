<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        $userId = $this->route('user');
        return [
            'username' => [
                'sometimes',
                'string',
                'max:50',
                Rule::unique('users')->ignore($userId, 'id_user'),
            ],
            'email' => [
                'sometimes',
                'email',
                Rule::unique('users')->ignore($userId, 'id_user'),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'sometimes|in:Ormawa,Mahasiswa,Kemahasiswaan,DPMBEM',
        ];
    }

    public function messages(): array
    {
        return [
            'username.string' => 'Username harus berupa teks',
            'username.max' => 'Username maksimal 50 karakter',
            'username.unique' => 'Username sudah terdaftar',
            'email.email' => 'Format Email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.string' => 'Password harus berupa teks',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi Password tidak cocok',
            'role.in' => 'Role yang dipilih tidak valid',
        ];
    }
}
