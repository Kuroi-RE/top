<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('proposal_kegiatan')
            ->where('nama_kegiatan', 'like', '%Unde%')
            ->orWhere('nama_kegiatan', 'like', '%Quia%')
            ->orWhere('nama_kegiatan', 'like', '%Neque%')
            ->orWhere('nama_kegiatan', 'like', '%Impedit%')
            ->orWhere('nama_kegiatan', 'like', '%lorem%')
            ->delete();
    }

    public function down(): void
    {
    }
};
