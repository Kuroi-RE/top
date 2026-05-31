<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviseProposalRequest extends FormRequest
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
        return $this->user()->id_user === $proposal->id_user;
    }

    public function rules(): array
    {
        return [
            'ajuan_triwulan' => 'required|in:I,II,III,IV',
            'risiko_proposal' => 'required|in:Rendah,Sedang,Tinggi',
            'nama_kegiatan' => 'required|string|max:150',
            'waktu_kegiatan' => 'required|date',
            'besar_ajuan' => 'required|numeric|min:100000',
            'catatan_revisi' => 'required|string',
            'file' => 'required|file|mimes:pdf|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'ajuan_triwulan.required' => 'Ajuan Triwulan wajib diisi',
            'ajuan_triwulan.in' => 'Ajuan Triwulan yang dipilih tidak valid',
            'risiko_proposal.required' => 'Risiko Proposal wajib diisi',
            'risiko_proposal.in' => 'Risiko Proposal yang dipilih tidak valid',
            'nama_kegiatan.required' => 'Nama Kegiatan wajib diisi',
            'nama_kegiatan.string' => 'Nama Kegiatan harus berupa teks',
            'nama_kegiatan.max' => 'Nama Kegiatan maksimal 150 karakter',
            'waktu_kegiatan.required' => 'Waktu Kegiatan wajib diisi',
            'waktu_kegiatan.date' => 'Waktu Kegiatan harus berupa tanggal yang valid',
            'besar_ajuan.required' => 'Besar Ajuan wajib diisi',
            'besar_ajuan.numeric' => 'Besar Ajuan harus berupa angka',
            'besar_ajuan.min' => 'Besar Ajuan minimal 100000',
            'catatan_revisi.required' => 'Catatan Revisi wajib diisi',
            'catatan_revisi.string' => 'Catatan Revisi harus berupa teks',
            'file.required' => 'File wajib diisi',
            'file.file' => 'File harus berupa file',
            'file.mimes' => 'Format File harus berupa pdf',
            'file.max' => 'File maksimal 5120 KB',
        ];
    }
}
