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
        Schema::table('prestasi', function (Blueprint $table) {
            $table->string('pelaksanaan', 50)->nullable()->after('kategori');
            $table->date('waktu_kompetisi')->nullable()->after('pelaksanaan');
            $table->date('tanggal_pengumuman')->nullable()->after('waktu_kompetisi');
            $table->string('klaster', 100)->nullable()->after('tanggal_pengumuman');
            $table->integer('jumlah_negara')->nullable()->after('klaster');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prestasi', function (Blueprint $table) {
            $table->dropColumn([
                'pelaksanaan',
                'waktu_kompetisi',
                'tanggal_pengumuman',
                'klaster',
                'jumlah_negara',
            ]);
        });
    }
};
