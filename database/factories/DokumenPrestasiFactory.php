<?php

namespace Database\Factories;

use App\Models\DokumenPrestasi;
use App\Models\Prestasi;
use Illuminate\Database\Eloquent\Factories\Factory;

class DokumenPrestasiFactory extends Factory
{
    protected $model = DokumenPrestasi::class;

    public function definition(): array
    {
        return [
            'id_prestasi' => Prestasi::factory(),
            'jenis_dokumen' => fake()->randomElement(['Sertifikat', 'Piala', 'Medali', 'Bukti Partisipasi', 'Foto Kegiatan']),
            'file' => 'documents/sample-document.pdf',
        ];
    }
}
