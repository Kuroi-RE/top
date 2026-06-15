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
            // INFRA-001 FIX: Skip if column already exists (after SQLite table recreate in migration 000005)
            // Also use string instead of enum to avoid SQLite CHECK constraint issues
            if (!Schema::hasColumn('proposal_kegiatan', 'category')) {
                $table->string('category', 20)->default('Ormawa')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('proposal_kegiatan', function (Blueprint $table) {
            if (Schema::hasColumn('proposal_kegiatan', 'category')) {
                $table->dropColumn('category');
            }
        });
    }
};
