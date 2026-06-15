<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProposalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isOrmawa() || $this->user()->isAdmin() || $this->user()->isMahasiswa();
    }

    public function rules(): array
    {
        return [
            'type' => 'nullable|string|in:mahasiswa,ormawa',
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
            'file' => ['required', 'file', 'mimes:pdf', 'min:1', 'max:5120', new \App\Rules\PdfMagicBytes()],
        ];
    }

        public function messages(): array
    {
        return [
            'ajuan_triwulan.required' => 'Ajuan Triwulan wajib diisi',
            'ajuan_triwulan.in' => 'Ajuan Triwulan yang dipilih tidak valid',
            'risiko_proposal.required' => 'Risiko Proposal wajib diisi',
            'risiko_proposal.in' => 'Risiko Proposal yang dipilih tidak valid',
            'no_telepon.required' => 'No Telepon wajib diisi',
            'no_telepon.string' => 'No Telepon harus berupa teks',
            'no_telepon.max' => 'No Telepon maksimal 15 karakter',
            'nama_kegiatan.required' => 'Nama Kegiatan wajib diisi',
            'nama_kegiatan.string' => 'Nama Kegiatan harus berupa teks',
            'nama_kegiatan.max' => 'Nama Kegiatan maksimal 150 karakter',
            'waktu_kegiatan.required' => 'Waktu Kegiatan wajib diisi',
            'waktu_kegiatan.date' => 'Waktu Kegiatan harus berupa tanggal yang valid',
            'tempat_kegiatan.required' => 'Tempat Kegiatan wajib diisi',
            'tempat_kegiatan.string' => 'Tempat Kegiatan harus berupa teks',
            'tempat_kegiatan.max' => 'Tempat Kegiatan maksimal 150 karakter',
            'besar_ajuan.required' => 'Besar Ajuan wajib diisi',
            'besar_ajuan.numeric' => 'Besar Ajuan harus berupa angka',
            'besar_ajuan.min' => 'Besar Ajuan minimal 100000',
            'nomor_rekening.required' => 'Nomor Rekening wajib diisi',
            'nomor_rekening.string' => 'Nomor Rekening harus berupa teks',
            'nomor_rekening.max' => 'Nomor Rekening maksimal 30 karakter',
            'nama_rekening.required' => 'Nama Rekening wajib diisi',
            'nama_rekening.string' => 'Nama Rekening harus berupa teks',
            'nama_rekening.max' => 'Nama Rekening maksimal 100 karakter',
            'nama_bank.required' => 'Nama Bank wajib diisi',
            'nama_bank.string' => 'Nama Bank harus berupa teks',
            'nama_bank.max' => 'Nama Bank maksimal 100 karakter',
            'honor_pelatih.required' => 'Honor Pelatih wajib diisi',
            'honor_pelatih.in' => 'Honor Pelatih yang dipilih tidak valid',
            'file.required' => 'File wajib diisi',
            'file.file' => 'File harus berupa file',
            'file.mimes' => 'Format File harus berupa pdf',
            'file.min' => 'File tidak boleh kosong (0 bytes)',
            'file.max' => 'File maksimal 5120 KB',
        ];
    }
}
