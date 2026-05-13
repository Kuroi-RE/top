<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\ProposalKegiatan;

$count = 0;
$proposals = ProposalKegiatan::with('lpj')
    ->where('status', 'Disetujui')
    ->get();

foreach ($proposals as $p) {
    if ($p->lpj->count() > 0) {
        $p->status = 'Cek LPJ';
        $p->save();
        $count++;
    }
}

echo "Updated $count proposals to 'Cek LPJ'.\n";
