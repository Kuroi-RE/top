<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class InformasiKegiatanCollection extends ResourceCollection
{
    public $collects = InformasiKegiatanResource::class;

    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
