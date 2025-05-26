<?php

namespace Database\Factories;

use App\Models\Estudiante;
use App\Models\User;
use App\Models\Grado;
use App\Models\Grupo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Estudiante>
 */
class EstudianteFactory extends Factory
{
    protected $model = Estudiante::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'grado_id' => Grado::inRandomOrder()->first()?->id ?? Grado::factory(),
            'grupo_id' => Grupo::inRandomOrder()->first()?->id ?? Grupo::factory(),
            'listaNumero' => $this->faker->numberBetween(1, 40),
        ];
    }
}
