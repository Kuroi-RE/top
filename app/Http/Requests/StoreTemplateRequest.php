<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'nama_template' => 'required|string|max:100|unique:template_dokumen',
            'jenis_template' => 'required|string|max:50',
            'file' => ['required', 'file', 'mimes:pdf', 'min:1', 'max:5120', new \App\Rules\PdfMagicBytes()],
        ];
    }

        public function messages(): array
    {
        return [
            'nama_template.required' => 'Nama Template wajib diisi',
            'nama_template.string' => 'Nama Template harus berupa teks',
            'nama_template.max' => 'Nama Template maksimal 100 karakter',
            'nama_template.unique' => 'Nama Template sudah terdaftar',
            'jenis_template.required' => 'Jenis Template wajib diisi',
            'jenis_template.string' => 'Jenis Template harus berupa teks',
            'jenis_template.max' => 'Jenis Template maksimal 50 karakter',
            'file.required' => 'File wajib diisi',
            'file.file' => 'File harus berupa file',
            'file.mimes' => 'Format File harus berupa pdf',
            'file.min' => 'File tidak boleh kosong (0 bytes)',
            'file.max' => 'File maksimal 5120 KB',
        ];
    }
}
