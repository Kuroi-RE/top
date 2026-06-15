<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviseLpjRequest extends FormRequest
{
    public function authorize(): bool
    {
        $id = $this->route('lpj');
        $type = $this->input('type', $this->query('type'));
        
        if ($type === 'mahasiswa' || ($this->user() && $this->user()->isMahasiswa())) {
            $lpj = \App\Models\LpjPrestasiMahasiswa::find($id);
        } else {
            $lpj = \App\Models\LpjKegiatan::find($id);
            if (!$lpj) {
                $lpj = \App\Models\LpjPrestasiMahasiswa::find($id);
            }
        }

        if (!$lpj) {
            return false;
        }

        return $this->user()->id_user === $lpj->proposal->id_user;
    }

    public function rules(): array
    {
        return [
            'file_lpj' => ['required', 'file', 'mimes:pdf', 'min:1', 'max:5120', new \App\Rules\PdfMagicBytes()],
            'tanggal_upload' => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'file_lpj.required' => 'File Lpj wajib diisi',
            'file_lpj.file' => 'File Lpj harus berupa file',
            'file_lpj.mimes' => 'Format File Lpj harus berupa pdf',
            'file_lpj.min' => 'File Lpj tidak boleh kosong (0 bytes)',
            'file_lpj.max' => 'File Lpj maksimal 5120 KB',
            'tanggal_upload.required' => 'Tanggal Upload wajib diisi',
            'tanggal_upload.date' => 'Tanggal Upload harus berupa tanggal yang valid',
        ];
    }
}
