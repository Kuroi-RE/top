<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prestasi', function (Blueprint $table) {
            $table->id('id_prestasi');
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
            $table->string('nama_kompetisi', 150);
            $table->string('penyelenggara', 150);
            $table->enum('tingkat', ['Regional', 'Nasional', 'Internasional']);
            $table->string('capaian', 100);
            $table->enum('kategori', ['Individu', 'Kelompok']);
            $table->enum('status_verifikasi', ['Pending', 'Valid', 'Invalid', 'Revision'])->default('Pending');
            $table->timestamps();
            
            $table->index('id_user');
            $table->index('status_verifikasi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prestasi');
    }
};
