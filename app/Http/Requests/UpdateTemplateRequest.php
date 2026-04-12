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
}
