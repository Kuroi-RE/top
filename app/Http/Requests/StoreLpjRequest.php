<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLpjRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isOrmawa() || $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'id_proposal' => 'required|exists:proposal_kegiatan,id_proposal',
            'file_lpj' => 'required|file|mimes:pdf|max:5120',
            'tanggal_upload' => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'id_proposal.required' => 'Proposal wajib dipilih',
            'id_proposal.exists' => 'Proposal tidak ditemukan',
            'file_lpj.required' => 'File LPJ wajib diupload',
            'file_lpj.mimes' => 'File harus berformat PDF',
            'file_lpj.max' => 'Ukuran file maksimal 5MB',
        ];
    }
}
