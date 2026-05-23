<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PrestasiCollection extends ResourceCollection
{
    public $collects = PrestasiResource::class;

    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
