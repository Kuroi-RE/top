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
            'judul.required' => 'Judul informasi wajib diisi',
            'caption.required' => 'Caption/deskripsi wajib diisi',
        ];
    }
}
