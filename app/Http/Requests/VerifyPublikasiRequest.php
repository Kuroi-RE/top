<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyPublikasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin() || $this->user()->isSuperAdmin();
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:Approved,Rejected,Revision',
            'catatan_admin' => 'nullable|string',
            'placement' => 'nullable|string|max:100',
        ];
    }
}
