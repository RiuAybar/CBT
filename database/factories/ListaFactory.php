<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Seguimiento;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lista>
 */
class ListaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'seguimiento_id' => Seguimiento::inRandomOrder()->first()?->id ?? Seguimiento::factory(),
            'alumno_id' => User::role('estudiante')->inRandomOrder()->first()?->id ?? User::factory(),
        ];
    }
}
