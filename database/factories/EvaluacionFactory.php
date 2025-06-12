<?php

namespace Database\Factories;

use App\Models\Lista;
use App\Models\Parcial;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Evaluacion>
 */
class EvaluacionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faltas = fake()->numberBetween(0, 5);
        $suma = fake()->randomFloat(2, 0, 100);
        $calificacionParcial = fake()->randomFloat(2, 0, 100);
        return [
            'lista_id' => Lista::inRandomOrder()->first()?->id ?? Lista::factory(),
            'parcial_id' => Parcial::inRandomOrder()->first()?->id ?? Parcial::factory(),
            'faltas' => $faltas,
            'suma' => $suma,
            'calificacion_parcial' => $calificacionParcial,
        ];
    }
}
