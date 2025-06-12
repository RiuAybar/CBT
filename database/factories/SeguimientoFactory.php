<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Materia;
use App\Models\Carrera;
use App\Models\Grupo;
use App\Models\Semestre;
use App\Models\Seguimiento;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Seguimiento>
 */
class SeguimientoFactory extends Factory
{
    protected $model = Seguimiento::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'materia_id' => Materia::inRandomOrder()->first()?->id ?? Materia::factory(),
            'semestre_id' => Semestre::inRandomOrder()->first()?->id ?? Semestre::factory(),
            // 'grado_id' => Grado::inRandomOrder()->first()?->id ?? Grado::factory(),
            'grupo_id' => Grupo::inRandomOrder()->first()?->id ?? Grupo::factory(),
            'carrera_id' => Carrera::inRandomOrder()->first()?->id ?? Carrera::factory(),
            'profesor_id' => User::role('profesor')->inRandomOrder()->first()?->id ?? User::factory(),
            'ano' => fake()->year(),
        ];
    }
}
