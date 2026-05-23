<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyPrestasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'status_verifikasi' => 'required|in:Valid,Invalid,Revision',
        ];
    }

        public function messages(): array
    {
        return [
            'status_verifikasi.required' => 'Status Verifikasi wajib diisi',
            'status_verifikasi.in' => 'Status Verifikasi yang dipilih tidak valid',
        ];
    }
}
