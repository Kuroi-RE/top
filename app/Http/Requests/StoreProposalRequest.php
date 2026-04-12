<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProposalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isOrmawa() || $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'ajuan_triwulan' => 'required|in:I,II,III,IV',
            'risiko_proposal' => 'required|in:Rendah,Sedang,Tinggi',
            'no_telepon' => 'required|string|max:15',
            'nama_kegiatan' => 'required|string|max:150',
            'waktu_kegiatan' => 'required|date',
            'tempat_kegiatan' => 'required|string|max:150',
            'besar_ajuan' => 'required|numeric|min:100000',
            'nomor_rekening' => 'required|string|max:30',
            'nama_rekening' => 'required|string|max:100',
            'nama_bank' => 'required|string|max:100',
            'honor_pelatih' => 'required|in:Ya,Tidak',
            'file' => 'required|file|mimes:pdf|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'ajuan_triwulan.required' => 'Ajuan triwulan wajib diisi',
            'file.required' => 'File proposal wajib diupload',
            'file.mimes' => 'File harus berformat PDF',
            'file.max' => 'Ukuran file maksimal 5MB',
            'besar_ajuan.min' => 'Besar ajuan minimal Rp 100.000',
        ];
    }
}
