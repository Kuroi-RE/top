<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviseProposalRequest extends FormRequest
{
    public function authorize(): bool
    {
        $proposal = $this->route('proposal');
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
}
