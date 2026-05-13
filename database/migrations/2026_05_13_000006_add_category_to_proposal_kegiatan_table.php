<?php
/*
  Created at: 2026-05-13
  Purpose: Add category column to proposal_kegiatan table to distinguish between Ormawa and Prestasi proposals.
*/

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proposal_kegiatan', function (Blueprint $table) {
            $table->enum('category', ['Ormawa', 'Prestasi'])->default('Ormawa')->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('proposal_kegiatan', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
