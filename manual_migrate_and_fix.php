<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\ProposalKegiatan;

echo "Checking for 'category' column...\n";

if (!Schema::hasColumn('proposal_kegiatan', 'category')) {
    echo "Column 'category' not found. Creating it now...\n";
    try {
        Schema::table('proposal_kegiatan', function (Blueprint $table) {
            $table->enum('category', ['Ormawa', 'Prestasi'])->default('Ormawa')->after('status');
        });
        echo "Column 'category' created successfully.\n";
    } catch (\Exception $e) {
        echo "Error creating column: " . $e->getMessage() . "\n";
        exit(1);
    }
} else {
    echo "Column 'category' already exists.\n";
}

echo "Updating existing proposal categories based on user roles...\n";

$proposals = ProposalKegiatan::with('user')->get();
$count = 0;

foreach ($proposals as $p) {
    $role = $p->user->role ?? '';
    // If role contains 'Ormawa' or is 'DPMBEM', it's Ormawa category.
    // Otherwise (Mahasiswa, Kemahasiswaan, etc.), it might be Prestasi if it's a student.
    // Let's check specifically for Mahasiswa role.
    $newCategory = ($role === 'Mahasiswa') ? 'Prestasi' : 'Ormawa';
    
    if ($p->category !== $newCategory) {
        $p->category = $newCategory;
        $p->save();
        echo "Updated '{$p->nama_kegiatan}' (User: {$p->user->username}, Role: {$role}) -> Category: {$newCategory}\n";
        $count++;
    }
}

echo "Successfully updated {$count} proposals.\n";
