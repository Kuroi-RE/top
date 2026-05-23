<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dosen_pendamping', function (Blueprint $table) {
            $table->id('id_dosen');
            $table->unsignedBigInteger('id_prestasi');
            $table->foreign('id_prestasi')->references('id_prestasi')->on('prestasi')->onDelete('cascade');
            $table->string('nama_dosen', 150);
            $table->char('nidn', 10)->nullable();
            $table->char('nip', 18)->nullable();
            $table->string('prodi', 100);
            $table->string('surat_tugas')->nullable();
            $table->timestamps();
            
            $table->index('id_prestasi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dosen_pendamping');
    }
};
