<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\ProposalKegiatan;

$proposals = ProposalKegiatan::with('lpj')->get();
foreach ($proposals as $p) {
    echo "ID: {$p->id_proposal} | Status: {$p->status} | LPJ Count: " . $p->lpj->count() . "\n";
}
