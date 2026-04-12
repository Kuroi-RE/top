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
        // Only add columns if they don't already exist
        if (!Schema::hasColumn('users', 'nim')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('nim', 20)->nullable()->after('email');
            });
        }
        if (!Schema::hasColumn('users', 'nama_depan')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('nama_depan')->nullable()->after('nim');
            });
        }
        if (!Schema::hasColumn('users', 'nama_belakang')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('nama_belakang')->nullable()->after('nama_depan');
            });
        }
        if (!Schema::hasColumn('users', 'prodi')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('prodi')->nullable()->after('nama_belakang');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'nim')) {
                $table->dropColumn('nim');
            }
            if (Schema::hasColumn('users', 'nama_depan')) {
                $table->dropColumn('nama_depan');
            }
            if (Schema::hasColumn('users', 'nama_belakang')) {
                $table->dropColumn('nama_belakang');
            }
            if (Schema::hasColumn('users', 'prodi')) {
                $table->dropColumn('prodi');
            }
        });
    }
};
