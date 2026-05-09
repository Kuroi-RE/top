<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        $templateId = $this->route('template');
        return [
            'nama_template' => 'sometimes|string|max:100|unique:template_dokumen,nama_template,' . $templateId . ',id_template',
            'jenis_template' => 'sometimes|string|max:50',
            'file' => 'nullable|file|mimes:pdf|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_template.string' => 'Nama Template harus berupa teks',
            'nama_template.max' => 'Nama Template maksimal 100 karakter',
            'nama_template.unique' => 'Nama Template sudah terdaftar',
            'jenis_template.string' => 'Jenis Template harus berupa teks',
            'jenis_template.max' => 'Jenis Template maksimal 50 karakter',
            'file.file' => 'File harus berupa file',
            'file.mimes' => 'Format File harus berupa pdf',
            'file.max' => 'File maksimal 5120 KB',
        ];
    }
}
