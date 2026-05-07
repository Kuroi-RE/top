<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Prestasi;
use App\Models\DokumenPrestasi;
use App\Models\AnggotaPrestasi;

class DummyPrestasiSeeder extends Seeder
{
    public function run(): void
    {
        $manggala = User::where('username', 'manggala')->first();

        if ($manggala) {
            $prestasiList = Prestasi::factory(10)
                ->for($manggala, 'user')
                ->create();

            foreach ($prestasiList as $p) {
                DokumenPrestasi::factory(2)->for($p)->create();

                if ($p->kategori === 'Kelompok') {
                    AnggotaPrestasi::factory(rand(2, 4))->for($p)->create();
                }
            }
        }
    }
}
