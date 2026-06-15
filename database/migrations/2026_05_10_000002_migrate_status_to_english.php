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

        // CATATAN: ALTER TABLE (DDL) pada MySQL memicu implicit commit,
        // sehingga membungkusnya dalam DB::transaction() menyebabkan error
        // "There is no active transaction". Statement dijalankan sekuensial.
        // Pola widen -> update -> narrow bersifat idempotent.
        if (true) {
            // ── proposal_kegiatan.status ──────────────────────────────────────
            // PENTING: kolom proposal_kegiatan.status sudah diubah menjadi
            // varchar(50) oleh migration 2026_05_13_000005 dan kini mendukung
            // nilai tambahan di luar 4 status dasar, yaitu 'Selesai', 'Cek LPJ',
            // dan 'Revisi LPJ' (lihat MonitoringController). JANGAN mengubah kolom
            // ini kembali menjadi ENUM karena nilai-nilai tersebut akan terpotong
            // (data truncated) dan hilang. Cukup normalisasi data lama dari
            // Indonesia -> English secara idempotent (nilai lain dipertahankan).
            DB::statement("
                UPDATE proposal_kegiatan SET status = CASE status
                    WHEN 'Menunggu'  THEN 'Pending'
                    WHEN 'Revisi'    THEN 'Revision'
                    WHEN 'Disetujui' THEN 'Approved'
                    WHEN 'Ditolak'   THEN 'Rejected'
                    ELSE status
                END
            ");

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
        }
    }

    /**
     * Reverse the migrations.
     *
     * Reverts status enum values from English back to Indonesian.
     */
    public function down(): void
    {
        $isMysql = DB::getDriverName() === 'mysql';

        // DDL auto-commit pada MySQL — tanpa DB::transaction (lihat catatan di up()).
        if (true) {
            // ── proposal_kegiatan.status ──────────────────────────────────────
            // Kolom ini adalah varchar(50) (lihat catatan di up()); tidak diubah
            // menjadi ENUM. Hanya kembalikan data ke nilai Indonesia.
            DB::statement("
                UPDATE proposal_kegiatan SET status = CASE status
                    WHEN 'Pending'  THEN 'Menunggu'
                    WHEN 'Revision' THEN 'Revisi'
                    WHEN 'Approved' THEN 'Disetujui'
                    WHEN 'Rejected' THEN 'Ditolak'
                    ELSE status
                END
            ");

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
        }
    }
};
