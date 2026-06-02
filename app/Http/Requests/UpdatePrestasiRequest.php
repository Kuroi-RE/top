<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePrestasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        $prestasi = $this->route('prestasi');

        // Hanya pemilik prestasi yang bisa update
        // Dan hanya boleh update jika status masih Menunggu atau Revisi
        return $this->user()->id_user === $prestasi->id_user
            && in_array($prestasi->status_verifikasi, ['Menunggu', 'Revisi']);
    }

    public function rules(): array
    {
        return [
            'nama_kompetisi' => 'sometimes|required|string|max:150',
            'penyelenggara'  => 'sometimes|required|string|max:150',
            'tingkat'        => 'sometimes|required|in:Regional,Nasional,Internasional',
            'capaian'        => 'sometimes|required|string|max:100',
            'kategori'       => 'sometimes|required|in:Individu,Kelompok',
            'mewakili_ormawa' => 'sometimes|required|in:ya,tidak',
            'pelaksanaan' => 'sometimes|nullable|string|max:50',
            'waktu_kompetisi' => 'sometimes|nullable|date',
            'tanggal_pengumuman' => 'sometimes|nullable|date',
            'klaster' => 'sometimes|nullable|string|max:100',
            'jumlah_negara' => 'sometimes|nullable|integer|min:1',
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
        ];
    }
}