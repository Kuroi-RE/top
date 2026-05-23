<?php

namespace Database\Factories;

use App\Models\TemplateDokumen;
use Illuminate\Database\Eloquent\Factories\Factory;

class TemplateDokumenFactory extends Factory
{
    protected $model = TemplateDokumen::class;

    public function definition(): array
    {
        $jenis = fake()->randomElement(['Proposal', 'LPJ', 'Sertifikat', 'MAK']);
        
        return [
            'nama_template' => 'Template ' . $jenis . ' ' . fake()->numerify('###'),
            'jenis_template' => $jenis,
            'file' => 'templates/template-' . strtolower($jenis) . '.pdf',
        ];
    }
}
