<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ProposalKegiatan;
use App\Models\User;

echo "Total Proposals: " . ProposalKegiatan::count() . "\n";
echo "Total Users: " . User::count() . "\n";

$roles = User::distinct()->pluck('role');
echo "Roles in User table: " . implode(', ', $roles->toArray()) . "\n";

$proposalsWithUsers = ProposalKegiatan::with('user')->get();
echo "Proposals with user roles:\n";
foreach ($proposalsWithUsers as $p) {
    echo "- " . $p->nama_kegiatan . " (User Role: " . ($p->user->role ?? 'N/A') . ")\n";
}
