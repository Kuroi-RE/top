<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TemplateDokumenResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_template' => $this->id_template,
            'nama_template' => $this->nama_template,
            'jenis_template' => $this->jenis_template,
            'file' => $this->file,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
