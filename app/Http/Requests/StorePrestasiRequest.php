<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePrestasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isMahasiswa();
    }

    public function rules(): array
    {
        return [
            'nama_kompetisi' => 'required|string|max:150',
            'penyelenggara' => 'required|string|max:150',
            'tingkat' => 'required|in:Regional,Nasional,Internasional',
            'capaian' => 'required|string|max:100',
            'kategori' => 'required|in:Individu,Kelompok',
            'dokumen' => 'required|array|min:1',
            'dokumen.*.jenis_dokumen' => 'required|string|max:100',
            'dokumen.*.file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_kompetisi.required' => 'Nama kompetisi wajib diisi',
            'dokumen.required' => 'Minimal 1 dokumen harus diupload',
            'dokumen.*.file.required' => 'File dokumen wajib diupload',
        ];
    }
}
