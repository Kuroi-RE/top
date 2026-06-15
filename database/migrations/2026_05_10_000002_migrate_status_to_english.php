<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Migrates status enum values from Indonesian to English for:
     * - proposal_kegiatan.status
     * - lpj_kegiatan.status_lpj
     * - prestasi.status_verifikasi
     *
     * On MySQL: uses a two-step approach within a single transaction:
     * 1. Widen enum to include both old and new values
     * 2. UPDATE data to new values
     * 3. Narrow enum to new English-only values
     *
     * On SQLite (used in tests): the original create migrations already use
     * English enum values, so only the UPDATE statements are run (no-ops on
     * fresh data). SQLite does not support ALTER TABLE MODIFY COLUMN.
     */
    public function up(): void
    {
        $isMysql = DB::getDriverName() === 'mysql';

        DB::transaction(function () use ($isMysql) {
            // ── proposal_kegiatan.status ──────────────────────────────────────

            if ($isMysql) {
                // Step 1: Widen enum to include both old (Indonesian) and new (English) values
                DB::statement("
                    ALTER TABLE proposal_kegiatan
                    MODIFY COLUMN status
                    ENUM('Menunggu','Revisi','Disetujui','Ditolak','Pending','Revision','Approved','Rejected')
                    NOT NULL DEFAULT 'Pending'
                ");
            }

            // Step 2: Migrate existing data to English values
            DB::statement("
                UPDATE proposal_kegiatan SET status = CASE status
                    WHEN 'Menunggu'  THEN 'Pending'
                    WHEN 'Revisi'    THEN 'Revision'
                    WHEN 'Disetujui' THEN 'Approved'
                    WHEN 'Ditolak'   THEN 'Rejected'
                    ELSE status
                END
            ");

            if ($isMysql) {
                // Step 3: Narrow enum to English-only values
                DB::statement("
                    ALTER TABLE proposal_kegiatan
                    MODIFY COLUMN status
                    ENUM('Pending','Revision','Approved','Rejected')
                    NOT NULL DEFAULT 'Pending'
                ");
            }

            // ── lpj_kegiatan.status_lpj ───────────────────────────────────────

            if ($isMysql) {
                // Step 1: Widen enum
                DB::statement("
                    ALTER TABLE lpj_kegiatan
                    MODIFY COLUMN status_lpj
                    ENUM('Menunggu','Revisi','Disetujui','Pending','Revision','Approved')
                    NOT NULL DEFAULT 'Pending'
                ");
            }

            // Step 2: Migrate data
            DB::statement("
                UPDATE lpj_kegiatan SET status_lpj = CASE status_lpj
                    WHEN 'Menunggu'  THEN 'Pending'
                    WHEN 'Revisi'    THEN 'Revision'
                    WHEN 'Disetujui' THEN 'Approved'
                    ELSE status_lpj
                END
            ");

            if ($isMysql) {
                // Step 3: Narrow enum
                DB::statement("
                    ALTER TABLE lpj_kegiatan
                    MODIFY COLUMN status_lpj
                    ENUM('Pending','Revision','Approved')
                    NOT NULL DEFAULT 'Pending'
                ");
            }

            // ── prestasi.status_verifikasi ────────────────────────────────────

            if ($isMysql) {
                // Step 1: Widen enum (note: 'Valid' is retained, 'Tidak Valid' → 'Invalid')
                DB::statement("
                    ALTER TABLE prestasi
                    MODIFY COLUMN status_verifikasi
                    ENUM('Menunggu','Valid','Tidak Valid','Revisi','Pending','Invalid','Revision')
                    NOT NULL DEFAULT 'Pending'
                ");
            }

            // Step 2: Migrate data
            DB::statement("
                UPDATE prestasi SET status_verifikasi = CASE status_verifikasi
                    WHEN 'Menunggu'   THEN 'Pending'
                    WHEN 'Tidak Valid' THEN 'Invalid'
                    WHEN 'Revisi'     THEN 'Revision'
                    ELSE status_verifikasi
                END
            ");

            if ($isMysql) {
                // Step 3: Narrow enum
                DB::statement("
                    ALTER TABLE prestasi
                    MODIFY COLUMN status_verifikasi
                    ENUM('Pending','Valid','Invalid','Revision')
                    NOT NULL DEFAULT 'Pending'
                ");
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * Reverts status enum values from English back to Indonesian.
     */
    public function down(): void
    {
        $isMysql = DB::getDriverName() === 'mysql';

        DB::transaction(function () use ($isMysql) {
            // ── proposal_kegiatan.status ──────────────────────────────────────

            if ($isMysql) {
                // Step 1: Widen enum to include both English and Indonesian values
                DB::statement("
                    ALTER TABLE proposal_kegiatan
                    MODIFY COLUMN status
                    ENUM('Pending','Revision','Approved','Rejected','Menunggu','Revisi','Disetujui','Ditolak')
                    NOT NULL DEFAULT 'Menunggu'
                ");
            }

            // Step 2: Revert data to Indonesian values
            DB::statement("
                UPDATE proposal_kegiatan SET status = CASE status
                    WHEN 'Pending'  THEN 'Menunggu'
                    WHEN 'Revision' THEN 'Revisi'
                    WHEN 'Approved' THEN 'Disetujui'
                    WHEN 'Rejected' THEN 'Ditolak'
                    ELSE status
                END
            ");

            if ($isMysql) {
                // Step 3: Narrow enum to Indonesian-only values
                DB::statement("
                    ALTER TABLE proposal_kegiatan
                    MODIFY COLUMN status
                    ENUM('Menunggu','Revisi','Disetujui','Ditolak')
                    NOT NULL DEFAULT 'Menunggu'
                ");
            }

            // ── lpj_kegiatan.status_lpj ───────────────────────────────────────

            if ($isMysql) {
                // Step 1: Widen enum
                DB::statement("
                    ALTER TABLE lpj_kegiatan
                    MODIFY COLUMN status_lpj
                    ENUM('Pending','Revision','Approved','Menunggu','Revisi','Disetujui')
                    NOT NULL DEFAULT 'Menunggu'
                ");
            }

            // Step 2: Revert data
            DB::statement("
                UPDATE lpj_kegiatan SET status_lpj = CASE status_lpj
                    WHEN 'Pending'  THEN 'Menunggu'
                    WHEN 'Revision' THEN 'Revisi'
                    WHEN 'Approved' THEN 'Disetujui'
                    ELSE status_lpj
                END
            ");

            if ($isMysql) {
                // Step 3: Narrow enum
                DB::statement("
                    ALTER TABLE lpj_kegiatan
                    MODIFY COLUMN status_lpj
                    ENUM('Menunggu','Revisi','Disetujui')
                    NOT NULL DEFAULT 'Menunggu'
                ");
            }

            // ── prestasi.status_verifikasi ────────────────────────────────────

            if ($isMysql) {
                // Step 1: Widen enum
                DB::statement("
                    ALTER TABLE prestasi
                    MODIFY COLUMN status_verifikasi
                    ENUM('Pending','Valid','Invalid','Revision','Menunggu','Tidak Valid','Revisi')
                    NOT NULL DEFAULT 'Menunggu'
                ");
            }

            // Step 2: Revert data
            DB::statement("
                UPDATE prestasi SET status_verifikasi = CASE status_verifikasi
                    WHEN 'Pending'  THEN 'Menunggu'
                    WHEN 'Invalid'  THEN 'Tidak Valid'
                    WHEN 'Revision' THEN 'Revisi'
                    ELSE status_verifikasi
                END
            ");

            if ($isMysql) {
                // Step 3: Narrow enum
                DB::statement("
                    ALTER TABLE prestasi
                    MODIFY COLUMN status_verifikasi
                    ENUM('Menunggu','Valid','Tidak Valid','Revisi')
                    NOT NULL DEFAULT 'Menunggu'
                ");
            }
        });
    }
};
