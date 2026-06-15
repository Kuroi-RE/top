<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Menyelaraskan enum prestasi.status_verifikasi ke nilai Indonesia
 * agar konsisten dengan seluruh kode aplikasi (PrestasiController::store,
 * VerifyPrestasiRequest, dan tampilan frontend) serta tabel status lainnya.
 *
 * Migration sebelumnya (migrate_status_to_english) mengubah kolom ini ke
 * English (Pending/Valid/Invalid/Revision) tetapi kode aplikasi tetap memakai
 * Indonesia (Menunggu/Valid/Tidak Valid/Revisi), menyebabkan error
 * "Data truncated for column 'status_verifikasi'".
 */
return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return; // SQLite/lainnya: kolom string, tidak perlu diubah
        }

        // 1) Perluas enum agar memuat kedua set nilai (English + Indonesia)
        DB::statement("
            ALTER TABLE prestasi
            MODIFY COLUMN status_verifikasi
            ENUM('Pending','Valid','Invalid','Revision','Menunggu','Tidak Valid','Revisi')
            NOT NULL DEFAULT 'Menunggu'
        ");

        // 2) Konversi data English -> Indonesia
        DB::statement("
            UPDATE prestasi SET status_verifikasi = CASE status_verifikasi
                WHEN 'Pending'  THEN 'Menunggu'
                WHEN 'Invalid'  THEN 'Tidak Valid'
                WHEN 'Revision' THEN 'Revisi'
                ELSE status_verifikasi
            END
        ");

        // 3) Persempit enum ke nilai Indonesia saja
        DB::statement("
            ALTER TABLE prestasi
            MODIFY COLUMN status_verifikasi
            ENUM('Menunggu','Valid','Tidak Valid','Revisi')
            NOT NULL DEFAULT 'Menunggu'
        ");
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        // Kembalikan ke English (sesuai migrate_status_to_english)
        DB::statement("
            ALTER TABLE prestasi
            MODIFY COLUMN status_verifikasi
            ENUM('Menunggu','Valid','Tidak Valid','Revisi','Pending','Invalid','Revision')
            NOT NULL DEFAULT 'Pending'
        ");

        DB::statement("
            UPDATE prestasi SET status_verifikasi = CASE status_verifikasi
                WHEN 'Menunggu'    THEN 'Pending'
                WHEN 'Tidak Valid' THEN 'Invalid'
                WHEN 'Revisi'      THEN 'Revision'
                ELSE status_verifikasi
            END
        ");

        DB::statement("
            ALTER TABLE prestasi
            MODIFY COLUMN status_verifikasi
            ENUM('Pending','Valid','Invalid','Revision')
            NOT NULL DEFAULT 'Pending'
        ");
    }
};
