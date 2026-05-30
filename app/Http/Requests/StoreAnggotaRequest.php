<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnggotaRequest extends FormRequest
{
    public function authorize(): bool
    {
        $prestasi = $this->route('prestasi');
        return $this->user()->id_user === $prestasi->id_user;
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:100',
            'nim' => 'required|string|max:12',
            'prodi' => 'required|string|max:100',
        ];
    }

        public function messages(): array
    {
        return [
            'nama.required' => 'Nama wajib diisi',
            'nama.string' => 'Nama harus berupa teks',
            'nama.max' => 'Nama maksimal 100 karakter',
            'nim.required' => 'Nim wajib diisi',
            'nim.string' => 'Nim harus berupa teks',
            'nim.max' => 'Nim maksimal 12 karakter',
            'prodi.required' => 'Prodi wajib diisi',
            'prodi.string' => 'Prodi harus berupa teks',
            'prodi.max' => 'Prodi maksimal 100 karakter',
        ];
    }
}
