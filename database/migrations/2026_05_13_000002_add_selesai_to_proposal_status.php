<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // For MySQL, we use a raw query to update the ENUM
        DB::statement("ALTER TABLE proposal_kegiatan MODIFY COLUMN status ENUM('Menunggu', 'Revisi', 'Disetujui', 'Ditolak', 'Selesai') DEFAULT 'Menunggu'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE proposal_kegiatan MODIFY COLUMN status ENUM('Menunggu', 'Revisi', 'Disetujui', 'Ditolak') DEFAULT 'Menunggu'");
    }
};
