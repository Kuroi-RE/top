<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyPublikasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        return $user->isAdmin() || $user->isSuperAdmin() || $user->isDpmbem()
            || $user->hasPermissionTo('Approve Publikasi');
    }

    protected function prepareForValidation()
    {
        if ($this->has('status')) {
            $statusMap = [
                'Disetujui' => 'Approved',
                'Revisi' => 'Revision',
                'Ditolak' => 'Rejected',
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
            'status' => 'required|in:Approved,Rejected,Revision',
            'catatan_admin' => 'required_if:status,Rejected,Revision|nullable|string',
            'placement' => 'nullable|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'catatan_admin.required_if' => 'Catatan harus diisi jika status Ditolak atau Revisi.',
        ];
    }
}
