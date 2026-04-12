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
            'nim' => 'required|string|max:12|unique:anggota_prestasi',
            'prodi' => 'required|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama anggota wajib diisi',
            'nim.unique' => 'NIM sudah terdaftar sebagai anggota',
        ];
    }
}
