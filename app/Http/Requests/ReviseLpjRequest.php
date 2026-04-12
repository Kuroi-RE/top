<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviseLpjRequest extends FormRequest
{
    public function authorize(): bool
    {
        $lpj = $this->route('lpj');
        return $this->user()->id_user === $lpj->proposal->id_user;
    }

    public function rules(): array
    {
        return [
            'file_lpj' => 'required|file|mimes:pdf|max:5120',
            'tanggal_upload' => 'required|date',
        ];
    }
}
