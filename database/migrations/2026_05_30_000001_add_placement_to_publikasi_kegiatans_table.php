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
        Schema::table('publikasi_kegiatans', function (Blueprint $table) {
            $table->string('placement')->default('keduanya')->after('catatan_admin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('publikasi_kegiatans', function (Blueprint $table) {
            $table->dropColumn('placement');
        });
    }
};
