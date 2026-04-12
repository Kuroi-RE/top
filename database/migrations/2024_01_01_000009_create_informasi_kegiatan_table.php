<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('informasi_kegiatan', function (Blueprint $table) {
            $table->id('id_informasi');
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
            $table->string('judul', 150);
            $table->enum('role', ['Ormawa', 'Kemahasiswaan']);
            $table->text('caption');
            $table->string('file')->nullable();
            $table->timestamps();
            
            $table->index('id_user');
            $table->index('role');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('informasi_kegiatan');
    }
};
