<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_user' => $this->id_user,
            'username' => $this->username,
            'email' => $this->email,
            'nim' => $this->nim,
            'nama_depan' => $this->nama_depan,
            'nama_belakang' => $this->nama_belakang,
            'prodi' => $this->prodi,
            'role' => $this->role,
            'ormawa_name' => $this->ormawa_name,
            'ormawa_type' => $this->ormawa_type,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
