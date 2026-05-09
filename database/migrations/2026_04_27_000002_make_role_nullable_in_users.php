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
        Schema::table('users', function (Blueprint $table) {
            // Make role column nullable for backward compatibility
            // Spatie will manage roles through model_has_roles table
            $table->string('role')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['Super Admin', 'Kemahasiswaan', 'DPMBEM', 'Ormawa', 'Mahasiswa'])->default('Mahasiswa')->change();
        });
    }
};
