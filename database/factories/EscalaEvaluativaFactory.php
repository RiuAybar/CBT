<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EscalaEvaluativa>
 */
class EscalaEvaluativaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nombres = [
            ['nombre' => 'Examen', 'abreviatura' => 'Exa'],
            ['nombre' => 'Actividad', 'abreviatura' => 'Act'],
            ['nombre' => 'Trabajo en Clase', 'abreviatura' => 'T.C.'],
            ['nombre' => 'Tarea en Equipo', 'abreviatura' => 'T.E.'],
        ];
         $item = fake()->randomElement($nombres);
        return [
            'nombre' => $item['nombre'],
            'abreviatura' => $item['abreviatura'],
        ];
    }
}
