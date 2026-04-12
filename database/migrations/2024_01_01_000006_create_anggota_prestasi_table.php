<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anggota_prestasi', function (Blueprint $table) {
            $table->id('id_anggota');
            $table->unsignedBigInteger('id_prestasi');
            $table->foreign('id_prestasi')->references('id_prestasi')->on('prestasi')->onDelete('cascade');
            $table->string('nama', 100);
            $table->string('nim', 12);
            $table->string('prodi', 100);
            $table->timestamps();
            
            $table->index('id_prestasi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggota_prestasi');
    }
};
