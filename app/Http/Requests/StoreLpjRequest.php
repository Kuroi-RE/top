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
            'id_proposal.required' => 'Id Proposal wajib diisi',
            'file_lpj.required' => 'File Lpj wajib diisi',
            'file_lpj.file' => 'File Lpj harus berupa file',
            'file_lpj.mimes' => 'Format File Lpj harus berupa pdf',
            'file_lpj.max' => 'File Lpj maksimal 5120 KB',
            'tanggal_upload.required' => 'Tanggal Upload wajib diisi',
            'tanggal_upload.date' => 'Tanggal Upload harus berupa tanggal yang valid',
        ];
    }
}
