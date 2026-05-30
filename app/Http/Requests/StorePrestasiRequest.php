<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePrestasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo('Create Prestasi') || $this->user()->isMahasiswa();
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
            'dokumen.*.file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ];
    }

        public function messages(): array
    {
        return [
            'nama_kompetisi.required' => 'Nama Kompetisi wajib diisi',
            'nama_kompetisi.string' => 'Nama Kompetisi harus berupa teks',
            'nama_kompetisi.max' => 'Nama Kompetisi maksimal 150 karakter',
            'penyelenggara.required' => 'Penyelenggara wajib diisi',
            'penyelenggara.string' => 'Penyelenggara harus berupa teks',
            'penyelenggara.max' => 'Penyelenggara maksimal 150 karakter',
            'tingkat.required' => 'Tingkat wajib diisi',
            'tingkat.in' => 'Tingkat yang dipilih tidak valid',
            'capaian.required' => 'Capaian wajib diisi',
            'capaian.string' => 'Capaian harus berupa teks',
            'capaian.max' => 'Capaian maksimal 100 karakter',
            'kategori.required' => 'Kategori wajib diisi',
            'kategori.in' => 'Kategori yang dipilih tidak valid',
            'dokumen.required' => 'Dokumen wajib diisi',
            'dokumen.array' => 'Dokumen harus berupa array',
            'dokumen.min' => 'Dokumen minimal 1',
            'dokumen.*.jenis_dokumen.required' => 'Jenis Dokumen wajib diisi',
            'dokumen.*.jenis_dokumen.string' => 'Jenis Dokumen harus berupa teks',
            'dokumen.*.jenis_dokumen.max' => 'Jenis Dokumen maksimal 100 karakter',
            'dokumen.*.file.required' => 'File wajib diisi',
            'dokumen.*.file.file' => 'File harus berupa file',
            'dokumen.*.file.mimes' => 'Format File harus berupa pdf, jpg, jpeg, png, doc, atau docx',
            'dokumen.*.file.max' => 'File maksimal 10 MB',
        ];
    }
}
