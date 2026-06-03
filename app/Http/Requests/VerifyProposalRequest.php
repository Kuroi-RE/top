<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyProposalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    protected function prepareForValidation()
    {
        if ($this->has('status')) {
            $statusMap = [
                'Disetujui' => 'Approved',
                'Revisi' => 'Revision',
                'Ditolak' => 'Rejected',
                'Selesai' => 'Approved',
                'Approved' => 'Approved',
                'Revision' => 'Revision',
                'Rejected' => 'Rejected'
            ];
            $this->merge([
                'status' => $statusMap[$this->status] ?? $this->status
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'type' => 'nullable|string|in:mahasiswa,ormawa',
            'status' => 'required|in:Approved,Revision,Rejected',
            'catatan_admin' => 'nullable|string',
            'anggaran_disetujui' => 'required_if:status,Approved|numeric|min:0',
            'file_lpj_keuangan' => 'nullable|file|mimes:pdf|max:10240',
        ];
    }

        public function messages(): array
    {
        return [
            'status.required' => 'Status wajib diisi',
            'status.in' => 'Status yang dipilih tidak valid',
            'catatan_admin.string' => 'Catatan Admin harus berupa teks',
            'anggaran_disetujui.required_if' => 'Anggaran Disetujui wajib diisi jika status Approved',
            'anggaran_disetujui.numeric' => 'Anggaran Disetujui harus berupa angka',
            'anggaran_disetujui.min' => 'Anggaran Disetujui minimal 0',
        ];
    }
}
