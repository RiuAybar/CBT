<?php

namespace Database\Factories;

use App\Models\Parcial;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Parcial>
 */
class ParcialFactory extends Factory
{
    protected $model = Parcial::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Usamos una variable estática para asegurar que 'nombre' se incremente con cada parcial creado
        static $parcialNumber = 1;
        return [
            'nombre' => 'Parcial ' .$parcialNumber++,
        ];
    }
}
