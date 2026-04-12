<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProposalRequest extends FormRequest
{
    public function authorize(): bool
    {
        $proposal = $this->route('proposal');
        return $this->user()->id_user === $proposal->id_user && 
               ($proposal->status === 'Menunggu' || $proposal->status === 'Revisi');
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
}
