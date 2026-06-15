<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('publikasi_kegiatans', function (Blueprint $table) {
            $table->longText('content')->nullable()->after('caption');
            $table->dropColumn('link');
        });
    }

    public function down(): void
    {
        Schema::table('publikasi_kegiatans', function (Blueprint $table) {
            $table->dropColumn('content');
            $table->string('link')->nullable()->after('caption');
        });
    }
};
