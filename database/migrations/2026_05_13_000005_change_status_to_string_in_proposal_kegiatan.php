<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * INFRA-001 / DEF-004 FIX: Change proposal_kegiatan status column from ENUM to VARCHAR.
 * This supports English status values (Pending, Approved, Revision, Rejected, etc.)
 * and removes the CHECK constraint that blocked SQLite testing.
 */
return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            // MySQL: MODIFY COLUMN to VARCHAR
            DB::statement("ALTER TABLE proposal_kegiatan MODIFY COLUMN status VARCHAR(50) DEFAULT 'Pending'");
        } elseif ($driver === 'sqlite') {
            // SQLite: Cannot ALTER COLUMN, must recreate table without CHECK constraint
            // Read existing data
            $rows = DB::table('proposal_kegiatan')->get();

            // Drop and recreate without enum CHECK constraint
            Schema::drop('proposal_kegiatan');

            Schema::create('proposal_kegiatan', function (Blueprint $table) {
                $table->id('id_proposal');
                $table->unsignedBigInteger('id_user');
                $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
                $table->string('ajuan_triwulan', 10);
                $table->string('risiko_proposal', 20);
                $table->string('no_telepon', 15);
                $table->string('nama_kegiatan', 150);
                $table->date('waktu_kegiatan');
                $table->string('tempat_kegiatan', 150);
                $table->decimal('besar_ajuan', 12, 2);
                $table->string('nomor_rekening', 30);
                $table->string('nama_rekening', 100);
                $table->string('nama_bank', 100);
                $table->string('honor_pelatih', 10)->default('Tidak');
                $table->string('file');
                $table->string('status', 50)->default('Pending');  // String, no ENUM check
                $table->decimal('anggaran_disetujui', 12, 2)->nullable();
                $table->text('catatan_admin')->nullable();
                $table->string('file_lpj_keuangan')->nullable();
                $table->string('category', 20)->default('Ormawa')->nullable();
                $table->timestamps();

                $table->index('id_user');
                $table->index('status');
            });

            // Restore data
            foreach ($rows as $row) {
                DB::table('proposal_kegiatan')->insert((array) $row);
            }
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE proposal_kegiatan MODIFY COLUMN status ENUM('Menunggu', 'Revisi', 'Disetujui', 'Ditolak', 'Selesai') DEFAULT 'Menunggu'");
        }
        // SQLite down() not feasible without full table recreation — skip
    }
};
