<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyLpjRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'status_lpj' => 'required|in:Disetujui,Revisi',
        ];
    }

    public function messages(): array
    {
        return [
            'status_lpj.required' => 'Status LPJ wajib diisi',
        ];
    }
}
