<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('publikasi_kegiatans', function (Blueprint $table) {
            $table->id('id_publikasi');
            $table->unsignedBigInteger('id_user');
            $table->string('judul');
            $table->string('ormawa');
            $table->text('caption');
            $table->string('link')->nullable();
            $table->string('poster');
            $table->string('status')->default('Menunggu');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publikasi_kegiatans');
    }
};
