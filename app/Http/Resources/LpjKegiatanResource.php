<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LpjKegiatanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_lpj' => $this->id_lpj,
            'id_proposal' => $this->id_proposal,
            'proposal' => new ProposalKegiatanResource($this->whenLoaded('proposal')),
            'file_lpj' => $this->file_lpj,
            'status_lpj' => $this->status_lpj,
            'tanggal_upload' => $this->tanggal_upload,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
