<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\DB;

try {
    DB::statement("ALTER TABLE proposal_kegiatan MODIFY COLUMN status VARCHAR(50) DEFAULT 'Menunggu'");
    echo "Successfully updated table schema.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
