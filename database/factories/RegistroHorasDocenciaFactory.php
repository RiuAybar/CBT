<?php

namespace Database\Factories;

use Faker\Factory as FakerFactory;
use App\Models\RegistroHorasDocencia;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RegistroHorasDocencia>
 */
class RegistroHorasDocenciaFactory extends Factory
{
    protected $model = RegistroHorasDocencia::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fakerEs = FakerFactory::create('es_ES'); // Forzamos aquí
        return [
            'mes' => strtoupper($fakerEs->monthName()),
            'horasImpartidas' => $fakerEs->numberBetween(10, 30),
        ];
    }
}
