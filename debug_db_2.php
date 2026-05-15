<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ProposalKegiatan;
use App\Models\User;

$data = [
    'total_proposals' => ProposalKegiatan::count(),
    'proposals_with_ormawa' => ProposalKegiatan::whereHas('user', function($q) {
        $q->where('role', 'like', '%Ormawa%');
    })->count(),
    'user_roles' => User::pluck('role')->toArray(),
    'proposals' => ProposalKegiatan::with('user')->get()->map(function($p) {
        return [
            'id' => $p->id_proposal,
            'title' => $p->nama_kegiatan,
            'user_role' => $p->user->role ?? 'N/A'
        ];
    })->toArray()
];

file_put_contents('debug_output.json', json_encode($data, JSON_PRETTY_PRINT));
echo "Debug data written to debug_output.json\n";
