<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Grado;
use App\Models\Grupo;
use App\Models\Image;
use App\Models\Carrera;
use App\Models\Materia;
use App\Models\Parcial;
use App\Models\Semestre;
use App\Models\Asistencia;
use App\Models\Estudiante;
use App\Models\Evaluacion;
use App\Models\Seguimiento;
use App\Models\NotasPorAspecto;
use Illuminate\Database\Seeder;
use App\Models\EscalaEvaluativa;
use Spatie\Permission\Models\Role;
use App\Models\RegistroHorasDocencia;
use Spatie\Permission\Models\Permission;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'estatus' => 'habilitado',
            'password' => bcrypt('$Super001'),
        ]);
        // Crear permisos
        $Permission1 = Permission::create(['name' => 'ver profesores', 'guard_name' => 'api']);
        $Permission2 = Permission::create(['name' => 'eliminar profesores', 'guard_name' => 'api']);

        // Crear rol y asignar permisos
        $role = Role::create(['name' => 'admin', 'guard_name' => 'api']);
        $role->givePermissionTo([$Permission1, $Permission2]);

        // Asignar rol a un usuario
        $user->assignRole('admin');

        //imagen
        // $user->image()->save(Image::factory()->create());

        Carrera::factory()->count(10)->create();

        // estudiantes 
        // Crear el rol de estudiante si no existe
        $roleEstudiante = Role::firstOrCreate(['name' => 'estudiante', 'guard_name' => 'api']);

        $roleOrientador = Role::firstOrCreate(['name' => 'orientador', 'guard_name' => 'api']);
        //Profesor
        Role::firstOrCreate(['name' => 'profesor', 'guard_name' => 'api']);
        $Profesor = User::create([
            'name' => 'Juan Pérez',
            'email' => 'juan@correo.com',
            'password' => bcrypt('secret'),
        ]);

        $Profesor->assignRole('profesor');

        // RegistroHorasDocencia::factory()->count(6)->create();

        Grado::factory()->count(3)->create();
        // Crear 6 grados (1° a 6°)
        $grados = Grado::all();

        // Crear 3 grupos (1 A, 2 B, 3 C)
        foreach ($grados as $grado) {
            Grupo::factory()->count(2)->create([
                'grado_id' => $grado->id, // ejemplo: 1A y 1B, etc.
            ]);
        }

        // Crear 6 semestre (1° a 6°)
        Semestre::factory()->count(6)->create();

        Parcial::factory()->count(3)->create();

        Materia::factory()->count(3)->create();




        // Crear 30 usuarios y estudiantes
        User::factory()
            ->count(30)
            ->create()
            ->each(function ($user) use ($roleEstudiante) {
                $user->assignRole($roleEstudiante); // asignar rol

                // $user->image()->save(Image::factory()->create());

                $estudiante = Estudiante::factory()->create([
                    'user_id' => $user->id,
                ]);

                $fechas = [];

                while (count($fechas) < 5) {
                    $fecha = fake()->dateTimeBetween('-1 month', 'now')->format('Y-m-d');

                    if (!in_array($fecha, $fechas)) {
                        $fechas[] = $fecha;

                        Asistencia::factory()->create([
                            'estudiante_id' => $estudiante->id,
                            'date' => $fecha,
                        ]);
                    }
                }
            });
        $escalas = [
            ['nombre' => 'Examen', 'abreviatura' => 'Exa'],
            ['nombre' => 'Actividad', 'abreviatura' => 'Act'],
            ['nombre' => 'Trabajo en Clase', 'abreviatura' => 'T.C.'],
            ['nombre' => 'Trabajo en Equipo', 'abreviatura' => 'T.E.'],
        ];
        foreach ($escalas as $escala) {
            EscalaEvaluativa::create($escala);
        }

        // Asegúrate de tener profesores y materias creadas
        Seguimiento::factory()->count(10)->create();

        Evaluacion::factory()->count(10)->create();

        // EscalaEvaluativa::factory()->count(4)->create();

        $materias = Materia::all();
        $parciales = Parcial::all();
        $escalas = EscalaEvaluativa::all();
        // dd($escalas);

        foreach ($materias as $materia) {
            foreach ($parciales as $parcial) {
                // Asignar 2-4 escalas aleatorias a cada materia+parcial
                $materia->escalasPorParcial()->attach(
                    $escalas->random(rand(2, 4))->pluck('id')->toArray(),
                    ['parcial_id' => $parcial->id]
                );
            }
        }

        // NotasPorAspecto::factory()->count(20)->create();
    }
}
