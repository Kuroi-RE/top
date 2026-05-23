<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProposalKegiatanCollection extends ResourceCollection
{
    public $collects = ProposalKegiatanResource::class;

    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
