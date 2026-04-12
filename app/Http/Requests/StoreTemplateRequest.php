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
            'file' => 'required|file|mimes:pdf|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_template.unique' => 'Template dengan nama tersebut sudah ada',
            'file.required' => 'File template wajib diupload',
        ];
    }
}
