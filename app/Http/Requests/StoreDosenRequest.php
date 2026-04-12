<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDosenRequest extends FormRequest
{
    public function authorize(): bool
    {
        $prestasi = $this->route('prestasi');
        return $this->user()->id_user === $prestasi->id_user;
    }

    public function rules(): array
    {
        return [
            'nama_dosen' => 'required|string|max:150',
            'nidn' => 'nullable|string|char:10',
            'nip' => 'nullable|string|char:18',
            'prodi' => 'required|string|max:100',
            'surat_tugas' => 'nullable|file|mimes:pdf|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_dosen.required' => 'Nama dosen wajib diisi',
            'prodi.required' => 'Program studi wajib diisi',
        ];
    }
}
