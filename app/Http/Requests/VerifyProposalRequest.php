<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyProposalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:Disetujui,Revisi,Ditolak',
            'catatan_admin' => 'nullable|string',
            'anggaran_disetujui' => 'required_if:status,Disetujui|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Status verifikasi wajib diisi',
            'anggaran_disetujui.required_if' => 'Anggaran yang disetujui wajib diisi jika status Disetujui',
        ];
    }
}
