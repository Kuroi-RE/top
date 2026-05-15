<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ProposalKegiatan;
use App\Models\User;

echo "Updating proposal categories based on user roles...\n";

$proposals = ProposalKegiatan::with('user')->get();
$count = 0;

foreach ($proposals as $p) {
    $role = $p->user->role ?? '';
    $newCategory = (str_contains($role, 'Ormawa') || $role === 'DPMBEM') ? 'Ormawa' : 'Prestasi';
    
    if ($p->category !== $newCategory) {
        $p->category = $newCategory;
        $p->save();
        echo "Updated '{$p->nama_kegiatan}' (User: {$p->user->username}) -> Category: {$newCategory}\n";
        $count++;
    }
}

echo "Successfully updated {$count} proposals.\n";
