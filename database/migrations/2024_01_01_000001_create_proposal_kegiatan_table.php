<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposal_kegiatan', function (Blueprint $table) {
            $table->id('id_proposal');
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
            $table->enum('ajuan_triwulan', ['I', 'II', 'III', 'IV']);
            $table->enum('risiko_proposal', ['Rendah', 'Sedang', 'Tinggi']);
            $table->string('no_telepon', 15);
            $table->string('nama_kegiatan', 150);
            $table->date('waktu_kegiatan');
            $table->string('tempat_kegiatan', 150);
            $table->decimal('besar_ajuan', 12, 2);
            $table->string('nomor_rekening', 30);
            $table->string('nama_rekening', 100);
            $table->string('nama_bank', 100);
            $table->enum('honor_pelatih', ['Ya', 'Tidak'])->default('Tidak');
            $table->string('file'); // path file PDF
            $table->enum('status', ['Menunggu', 'Revisi', 'Disetujui', 'Ditolak'])->default('Menunggu');
            $table->decimal('anggaran_disetujui', 12, 2)->nullable();
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
            
            $table->index('id_user');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_kegiatan');
    }
};
