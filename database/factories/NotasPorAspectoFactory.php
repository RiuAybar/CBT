<?php

namespace Database\Factories;

use App\Models\Evaluacion;
use App\Models\EscalaEvaluativa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NotasPorAspecto>
 */
class NotasPorAspectoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Puedes modificar estos valores para reflejar tu lógica real de puntajes
        $cantidad = $this->faker->randomFloat(2, 0, 10);  // Por ejemplo, de 0 a 10
        $puntaje = $this->faker->randomFloat(2, 0, $cantidad);  // No mayor a la cantidad

        return [
            'evaluacion_id' => Evaluacion::factory(),
            'escala_evaluativa_id' => EscalaEvaluativa::factory(),
            'cantidad_obtenida' => $cantidad,
            'puntaje_obtenido' => $puntaje,
        ];
    }
}
