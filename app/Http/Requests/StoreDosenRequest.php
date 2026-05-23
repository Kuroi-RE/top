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
            'nama' => 'required|string|max:150',
            'nip' => 'nullable|string|digits:18',
            'nidn' => 'nullable|string|digits:10',
            'prodi' => 'required|string|max:100',
            'surat_tugas' => 'nullable|file|mimes:pdf|max:5120',
        ];
    }

        public function messages(): array
    {
        return [
            'nama.required' => 'Nama wajib diisi',
            'nama.string' => 'Nama harus berupa teks',
            'nama.max' => 'Nama maksimal 150 karakter',
            'nip.string' => 'Nip harus berupa teks',
            'nip.digits' => 'Nip harus 18 digit',
            'nidn.string' => 'Nidn harus berupa teks',
            'nidn.digits' => 'Nidn harus 10 digit',
            'prodi.required' => 'Prodi wajib diisi',
            'prodi.string' => 'Prodi harus berupa teks',
            'prodi.max' => 'Prodi maksimal 100 karakter',
            'surat_tugas.file' => 'Surat Tugas harus berupa file',
            'surat_tugas.mimes' => 'Format Surat Tugas harus berupa pdf',
            'surat_tugas.max' => 'Surat Tugas maksimal 5120 KB',
        ];
    }
}
