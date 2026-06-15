<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Menyelaraskan enum lpj_kegiatan.status_lpj ke nilai Indonesia
 * (Menunggu/Revisi/Disetujui) agar konsisten dengan kode aplikasi
 * (LpjController::store & submitRevision pakai 'Menunggu', verify pakai
 * 'Disetujui'/'Revisi').
 *
 * Migration migrate_status_to_english sebelumnya mengubah kolom ini ke English,
 * tetapi kode LPJ tetap memakai Indonesia. Migration ini mengembalikannya.
 *
 * Pola widen -> update -> narrow, idempotent, tanpa DB::transaction
 * (DDL MySQL auto-commit).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        // 1) Perluas enum agar memuat English + Indonesia
        DB::statement("
            ALTER TABLE lpj_kegiatan
            MODIFY COLUMN status_lpj
            ENUM('Pending','Revision','Approved','Menunggu','Revisi','Disetujui')
            NOT NULL DEFAULT 'Menunggu'
        ");

        // 2) Konversi data English -> Indonesia
        DB::statement("
            UPDATE lpj_kegiatan SET status_lpj = CASE status_lpj
                WHEN 'Pending'  THEN 'Menunggu'
                WHEN 'Revision' THEN 'Revisi'
                WHEN 'Approved' THEN 'Disetujui'
                ELSE status_lpj
            END
        ");

        // 3) Persempit enum ke Indonesia saja
        DB::statement("
            ALTER TABLE lpj_kegiatan
            MODIFY COLUMN status_lpj
            ENUM('Menunggu','Revisi','Disetujui')
            NOT NULL DEFAULT 'Menunggu'
        ");
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("
            ALTER TABLE lpj_kegiatan
            MODIFY COLUMN status_lpj
            ENUM('Menunggu','Revisi','Disetujui','Pending','Revision','Approved')
            NOT NULL DEFAULT 'Pending'
        ");

        DB::statement("
            UPDATE lpj_kegiatan SET status_lpj = CASE status_lpj
                WHEN 'Menunggu'  THEN 'Pending'
                WHEN 'Revisi'    THEN 'Revision'
                WHEN 'Disetujui' THEN 'Approved'
                ELSE status_lpj
            END
        ");

        DB::statement("
            ALTER TABLE lpj_kegiatan
            MODIFY COLUMN status_lpj
            ENUM('Pending','Revision','Approved')
            NOT NULL DEFAULT 'Pending'
        ");
    }
};
