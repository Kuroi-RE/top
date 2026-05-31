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
            'status_lpj' => 'required|in:Approved,Revision',
            'catatan_admin' => 'nullable|string',
        ];
    }

        public function messages(): array
    {
        return [
            'status_lpj.required' => 'Status Lpj wajib diisi',
            'status_lpj.in' => 'Status Lpj yang dipilih tidak valid',
        ];
    }
}
