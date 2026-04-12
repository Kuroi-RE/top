<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Public endpoint - no authorization needed
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'nim' => [
                'required',
                'string',
                'max:20',
                'unique:users,nim',
            ],
            'nama_depan' => [
                'required',
                'string',
                'max:100',
            ],
            'nama_belakang' => [
                'required',
                'string',
                'max:100',
            ],
            'prodi' => [
                'required',
                'string',
                'max:100',
            ],
            'email' => [
                'required',
                'email',
                'unique:users,email',
                'max:255',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nim.required' => 'NIM tidak boleh kosong',
            'nim.string' => 'NIM harus berupa teks',
            'nim.max' => 'NIM tidak boleh lebih dari 20 karakter',
            'nim.unique' => 'NIM sudah terdaftar',
            'nama_depan.required' => 'Nama depan tidak boleh kosong',
            'nama_depan.string' => 'Nama depan harus berupa teks',
            'nama_belakang.required' => 'Nama belakang tidak boleh kosong',
            'nama_belakang.string' => 'Nama belakang harus berupa teks',
            'prodi.required' => 'Program studi tidak boleh kosong',
            'prodi.string' => 'Program studi harus berupa teks',
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password tidak boleh kosong',
            'password.string' => 'Password harus berupa teks',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Password tidak cocok dengan konfirmasi',
        ];
    }
}
