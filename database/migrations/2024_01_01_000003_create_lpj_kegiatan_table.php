<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lpj_kegiatan', function (Blueprint $table) {
            $table->id('id_lpj');
            $table->unsignedBigInteger('id_proposal');
            $table->foreign('id_proposal')->references('id_proposal')->on('proposal_kegiatan')->onDelete('cascade');
            $table->string('file_lpj');
            $table->enum('status_lpj', ['Pending', 'Revision', 'Approved'])->default('Pending');
            $table->date('tanggal_upload');
            $table->timestamps();
            
            $table->index('id_proposal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lpj_kegiatan');
    }
};
