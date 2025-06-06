<?php

namespace Database\Factories;

use App\Models\Semestre;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Semestre>
 */
class SemestreFactory extends Factory
{
    protected $model = Semestre::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $semestre = 1;
        return [
            'nombre' => $semestre++,
        ];
    }
}
