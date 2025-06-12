<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Materia>
 */
class MateriaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $materias = [
            'Matemáticas',
            'Lengua y Literatura',
            'Ciencias Naturales',
            'Ciencias Sociales',
            'Inglés',
            'Educación Física',
            'Arte',
            'Informática',
            'Filosofía',
            'Biología',
            'Química',
            'Física',
            'Historia',
            'Geografía',
            'Educación Cívica',
        ];
        return [
            'nombre' => $this->faker->unique()->randomElement($materias),
        ];
    }
}
