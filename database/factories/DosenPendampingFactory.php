<?php

namespace Database\Factories;

use App\Models\DosenPendamping;
use App\Models\Prestasi;
use Illuminate\Database\Eloquent\Factories\Factory;

class DosenPendampingFactory extends Factory
{
    protected $model = DosenPendamping::class;

    public function definition(): array
    {
        return [
            'id_prestasi' => Prestasi::factory(),
            'nama_dosen' => fake()->name(),
            'nidn' => fake()->numerify('0##########'),
            'nip' => fake()->numerify('##############'),
            'prodi' => fake()->randomElement(['Teknik Informatika', 'Teknik Elektro', 'Manajemen Informatika', 'Sistem Informasi']),
            'surat_tugas' => null,
        ];
    }
}
