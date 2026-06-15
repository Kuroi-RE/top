<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proposal_kegiatan', function (Blueprint $table) {
            // INFRA-001 FIX: Skip if column already exists (e.g., after SQLite table recreate in migration 000005)
            if (!Schema::hasColumn('proposal_kegiatan', 'file_lpj_keuangan')) {
                $table->string('file_lpj_keuangan')->nullable()->after('catatan_admin');
            }
        });
    }

    public function down(): void
    {
        Schema::table('proposal_kegiatan', function (Blueprint $table) {
            $table->dropColumn('file_lpj_keuangan');
        });
    }
};
