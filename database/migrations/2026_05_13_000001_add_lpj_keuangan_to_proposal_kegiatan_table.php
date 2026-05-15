<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proposal_kegiatan', function (Blueprint $table) {
            $table->string('file_lpj_keuangan')->nullable()->after('catatan_admin');
        });
    }

    public function down(): void
    {
        Schema::table('proposal_kegiatan', function (Blueprint $table) {
            $table->dropColumn('file_lpj_keuangan');
        });
    }
};
