<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            // For MySQL, update the ENUM to include 'Selesai'
            DB::statement("ALTER TABLE proposal_kegiatan MODIFY COLUMN status ENUM('Menunggu', 'Revisi', 'Disetujui', 'Ditolak', 'Selesai') DEFAULT 'Menunggu'");
        }
        // SQLite does not support MODIFY COLUMN — status column remains as-is (VARCHAR in SQLite)
    }

    public function down(): void
    {
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE proposal_kegiatan MODIFY COLUMN status ENUM('Menunggu', 'Revisi', 'Disetujui', 'Ditolak') DEFAULT 'Menunggu'");
        }
    }
};
