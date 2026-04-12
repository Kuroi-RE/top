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
}
