<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInformasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isOrmawa() || $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'judul' => 'required|string|max:150',
            'role' => 'required|in:Ormawa,Kemahasiswaan',
            'caption' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ];
    }

        public function messages(): array
    {
        return [
            'judul.required' => 'Judul wajib diisi',
            'judul.string' => 'Judul harus berupa teks',
            'judul.max' => 'Judul maksimal 150 karakter',
            'role.required' => 'Role wajib diisi',
            'role.in' => 'Role yang dipilih tidak valid',
            'caption.required' => 'Caption wajib diisi',
            'caption.string' => 'Caption harus berupa teks',
            'file.file' => 'File harus berupa file',
            'file.mimes' => 'Format File harus berupa pdf,jpg,jpeg,png,doc,docx',
            'file.max' => 'File maksimal 5120 KB',
        ];
    }
}
