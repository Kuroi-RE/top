<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('revisi_proposal', function (Blueprint $table) {
            $table->id('id_revisi');
            $table->unsignedBigInteger('id_proposal');
            $table->foreign('id_proposal')->references('id_proposal')->on('proposal_kegiatan')->onDelete('cascade');
            $table->enum('ajuan_triwulan', ['I', 'II', 'III', 'IV']);
            $table->enum('risiko_proposal', ['Rendah', 'Sedang', 'Tinggi']);
            $table->string('nama_kegiatan', 150);
            $table->date('waktu_kegiatan');
            $table->decimal('besar_ajuan', 12, 2);
            $table->text('catatan_revisi');
            $table->string('file'); // path file PDF
            $table->timestamps();
            
            $table->index('id_proposal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('revisi_proposal');
    }
};
