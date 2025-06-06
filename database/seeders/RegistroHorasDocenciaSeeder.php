<?php

namespace Database\Seeders;

use App\Models\Carrera;
use Illuminate\Database\Seeder;
use App\Models\RegistroHorasDocencia;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RegistroHorasDocenciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $carreras = Carrera::pluck('id')->toArray(); // Solo IDs
        $registros = [
            ['mes' => 'FEBRERO', 'horasImpartidas' => 20],
            ['mes' => 'MARZO',   'horasImpartidas' => 24],
            ['mes' => 'ABRIL',   'horasImpartidas' => 15],
            ['mes' => 'MAYO',    'horasImpartidas' => 24],
            ['mes' => 'JUNIO',   'horasImpartidas' => 21],
            ['mes' => 'JULIO',   'horasImpartidas' => 12],
        ];
        foreach ($registros as $registro) {
            RegistroHorasDocencia::create([
                    ...$registro,
                    'carrera_id' => fake()->randomElement($carreras),
            ]);
        }
    }
}
