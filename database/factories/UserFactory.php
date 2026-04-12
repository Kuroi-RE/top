<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'username' => fake()->unique()->userName(),
            'nim' => fake()->unique()->numerify('###########'),
            'nama_depan' => fake()->firstName(),
            'nama_belakang' => fake()->lastName(),
            'prodi' => fake()->randomElement(['Teknik Informatika', 'Teknik Elektro', 'Manajemen Informatika', 'Sistem Informasi']),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => 'Mahasiswa',
            'is_active' => true,
            'remember_token' => Str::random(10),
            'ormawa_type' => null,
            'ormawa_name' => null,
        ];
    }

    public function superAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'Super Admin',
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'Kemahasiswaan',
        ]);
    }

    public function ormawa(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'Ormawa',
        ]);
    }

    public function ormawaInstitusi(string $name = null): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'Ormawa',
            'ormawa_type' => 'institusi',
            'ormawa_name' => $name ?? fake()->company(),
        ]);
    }

    public function ormawaProdi(string $name = null): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'Ormawa',
            'ormawa_type' => 'prodi',
            'ormawa_name' => $name ?? fake()->word(),
        ]);
    }

    public function mahasiswa(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'Mahasiswa',
        ]);
    }

    public function dpmbem(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'DPMBEM',
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
