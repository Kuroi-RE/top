<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lpj_prestasi_mahasiswa', function (Blueprint $table) {
            $table->id('id_lpj');
            $table->unsignedBigInteger('id_proposal');
            $table->foreign('id_proposal')->references('id_proposal')->on('proposal_prestasi_mahasiswa')->onDelete('cascade');
            $table->string('file_lpj');
            $table->enum('status_lpj', ['Menunggu', 'Revisi', 'Disetujui'])->default('Menunggu');
            $table->text('catatan_admin')->nullable();
            $table->date('tanggal_upload');
            $table->timestamps();
            
            $table->index('id_proposal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lpj_prestasi_mahasiswa');
    }
};
