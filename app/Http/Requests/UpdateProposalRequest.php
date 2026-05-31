<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProposalRequest extends FormRequest
{
    public function authorize(): bool
    {
        $proposalId = $this->route('proposal');
        $proposal = \App\Models\ProposalKegiatan::find($proposalId);
        if (!$proposal) {
            $proposal = \App\Models\ProposalPrestasiMahasiswa::find($proposalId);
        }
        if (!$proposal) {
            return false;
        }
        return $this->user()->id_user === $proposal->id_user && 
               ($proposal->status === 'Pending' || $proposal->status === 'Revision' || $proposal->status === 'Menunggu' || $proposal->status === 'Revisi');
    }

    public function rules(): array
    {
        return [
            'ajuan_triwulan' => 'sometimes|in:I,II,III,IV',
            'risiko_proposal' => 'sometimes|in:Rendah,Sedang,Tinggi',
            'no_telepon' => 'sometimes|string|max:15',
            'nama_kegiatan' => 'sometimes|string|max:150',
            'waktu_kegiatan' => 'sometimes|date',
            'tempat_kegiatan' => 'sometimes|string|max:150',
            'besar_ajuan' => 'sometimes|numeric|min:100000',
            'nomor_rekening' => 'sometimes|string|max:30',
            'nama_rekening' => 'sometimes|string|max:100',
            'nama_bank' => 'sometimes|string|max:100',
            'honor_pelatih' => 'sometimes|in:Ya,Tidak',
            'file' => 'nullable|file|mimes:pdf|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'ajuan_triwulan.in' => 'Ajuan Triwulan yang dipilih tidak valid',
            'risiko_proposal.in' => 'Risiko Proposal yang dipilih tidak valid',
            'no_telepon.string' => 'No Telepon harus berupa teks',
            'no_telepon.max' => 'No Telepon maksimal 15 karakter',
            'nama_kegiatan.string' => 'Nama Kegiatan harus berupa teks',
            'nama_kegiatan.max' => 'Nama Kegiatan maksimal 150 karakter',
            'waktu_kegiatan.date' => 'Waktu Kegiatan harus berupa tanggal yang valid',
            'tempat_kegiatan.string' => 'Tempat Kegiatan harus berupa teks',
            'tempat_kegiatan.max' => 'Tempat Kegiatan maksimal 150 karakter',
            'besar_ajuan.numeric' => 'Besar Ajuan harus berupa angka',
            'besar_ajuan.min' => 'Besar Ajuan minimal 100000',
            'nomor_rekening.string' => 'Nomor Rekening harus berupa teks',
            'nomor_rekening.max' => 'Nomor Rekening maksimal 30 karakter',
            'nama_rekening.string' => 'Nama Rekening harus berupa teks',
            'nama_rekening.max' => 'Nama Rekening maksimal 100 karakter',
            'nama_bank.string' => 'Nama Bank harus berupa teks',
            'nama_bank.max' => 'Nama Bank maksimal 100 karakter',
            'honor_pelatih.in' => 'Honor Pelatih yang dipilih tidak valid',
            'file.file' => 'File harus berupa file',
            'file.mimes' => 'Format File harus berupa pdf',
            'file.max' => 'File maksimal 5120 KB',
        ];
    }
}
