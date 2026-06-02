<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePublikasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isOrmawa();
    }

    public function rules(): array
    {
        return [
            'judul' => 'required|string|max:255',
            'ormawa' => 'required|string|max:255',
            'caption' => 'required|string',
            'link' => 'nullable|url|max:500',
            'poster' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ];
    }
}
