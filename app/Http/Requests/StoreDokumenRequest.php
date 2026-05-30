<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDokumenRequest extends FormRequest
{
    public function authorize(): bool
    {
        $prestasi = $this->route('prestasi');
        return $this->user()->id_user === $prestasi->id_user;
    }

    public function rules(): array
    {
        return [
            'jenis_dokumen' => 'required|string|max:100',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ];
    }

    public function messages(): array
    {
        return [
            'jenis_dokumen.required' => 'Jenis Dokumen wajib diisi',
            'jenis_dokumen.string' => 'Jenis Dokumen harus berupa teks',
            'jenis_dokumen.max' => 'Jenis Dokumen maksimal 100 karakter',
            'file.required' => 'File wajib diisi',
            'file.file' => 'File harus berupa berkas',
            'file.mimes' => 'Format file harus berupa pdf, jpg, jpeg, png, doc, atau docx',
            'file.max' => 'File maksimal 10 MB',
        ];
    }
}
