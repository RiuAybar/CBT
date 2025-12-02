<?php

namespace Database\Factories;

use App\Models\Seguimiento;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SeguimientoHorarios>
 */
class SeguimientoHorarioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Días posibles
        $dias = ['LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES', 'SABADO', 'DOMINGO'];

        // Hora inicio entre 7:00 y 18:00
        $horaInicio = $this->faker->dateTimeBetween('07:00', '18:00');

        // Hora fin entre 30 y 120 minutos después
        $horaFin = (clone $horaInicio)->modify('+' . rand(30, 120) . ' minutes');

        return [
            'seguimiento_id' => Seguimiento::inRandomOrder()->first()?->id ?? Seguimiento::factory(),
            'dia' => $this->faker->randomElement($dias),
            'hora_inicio' => $horaInicio->format('H:i'),
            'hora_fin' => $horaFin->format('H:i'),
        ];
    }
}
