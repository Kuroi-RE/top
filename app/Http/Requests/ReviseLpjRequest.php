<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviseLpjRequest extends FormRequest
{
    public function authorize(): bool
    {
        $lpj = $this->route('lpj');
        return $this->user()->id_user === $lpj->proposal->id_user;
    }

    public function rules(): array
    {
        return [
            'file_lpj' => 'required|file|mimes:pdf|max:5120',
            'tanggal_upload' => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'file_lpj.required' => 'File Lpj wajib diisi',
            'file_lpj.file' => 'File Lpj harus berupa file',
            'file_lpj.mimes' => 'Format File Lpj harus berupa pdf',
            'file_lpj.max' => 'File Lpj maksimal 5120 KB',
            'tanggal_upload.required' => 'Tanggal Upload wajib diisi',
            'tanggal_upload.date' => 'Tanggal Upload harus berupa tanggal yang valid',
        ];
    }
}
