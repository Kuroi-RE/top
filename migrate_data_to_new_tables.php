<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ProposalKegiatan;
use App\Models\ProposalPrestasiMahasiswa;
use App\Models\ProposalPrestasiOrmawa;
use App\Models\User;

echo "Migrating data from proposal_kegiatan to new tables...\n";

$proposals = ProposalKegiatan::with('user')->get();
$count = 0;

foreach ($proposals as $p) {
    $role = $p->user->role ?? '';
    $category = $p->category ?? 'Ormawa';
    
    $targetModel = null;
    
    if ($p->user->isMahasiswa()) {
        $targetModel = new ProposalPrestasiMahasiswa();
    } elseif ($category === 'Prestasi') {
        $targetModel = new ProposalPrestasiOrmawa();
    }
    
    if ($targetModel) {
        $data = $p->toArray();
        unset($data['id_proposal']); // Let it auto-increment
        unset($data['category']); // Not in new tables
        
        $targetModel->fill($data);
        $targetModel->save();
        
        echo "Moved '{$p->nama_kegiatan}' to " . get_class($targetModel) . "\n";
        
        // Optionally delete from old table to avoid confusion?
        // No, let's keep it for safety for now, or the user might want it deleted.
        // Actually, the dashboards now look at the new tables, so the old ones are "ghosts".
        $count++;
    }
}

echo "Successfully migrated {$count} proposals.\n";
