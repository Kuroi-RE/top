<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePublikasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isOrmawa() || $this->user()->isAdmin() || $this->user()->isSuperAdmin();
    }

    public function rules(): array
    {
        return [
            'judul' => 'sometimes|string|max:255',
            'ormawa' => 'sometimes|string|max:255',
            'caption' => 'sometimes|string',
            'link' => 'nullable|url|max:500',
            'poster' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ];
    }
}
