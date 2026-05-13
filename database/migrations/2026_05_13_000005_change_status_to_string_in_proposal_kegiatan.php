<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Change from ENUM to VARCHAR to support 'Cek LPJ' and 'Revisi LPJ'
        DB::statement("ALTER TABLE proposal_kegiatan MODIFY COLUMN status VARCHAR(50) DEFAULT 'Menunggu'");
    }

    public function down(): void
    {
        // Revert to ENUM if needed
        DB::statement("ALTER TABLE proposal_kegiatan MODIFY COLUMN status ENUM('Menunggu', 'Revisi', 'Disetujui', 'Ditolak', 'Selesai') DEFAULT 'Menunggu'");
    }
};
