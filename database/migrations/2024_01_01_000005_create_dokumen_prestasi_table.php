<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen_prestasi', function (Blueprint $table) {
            $table->id('id_dokumen');
            $table->unsignedBigInteger('id_prestasi');
            $table->foreign('id_prestasi')->references('id_prestasi')->on('prestasi')->onDelete('cascade');
            $table->string('jenis_dokumen', 100);
            $table->string('file');
            $table->timestamps();
            
            $table->index('id_prestasi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_prestasi');
    }
};
