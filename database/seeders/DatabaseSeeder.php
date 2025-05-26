<?php

namespace Database\Seeders;

use App\Models\Grado;
use App\Models\Grupo;
use App\Models\Estudiante;
use App\Models\Asistencia;
use App\Models\Materia;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
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
        $Permission1 = Permission::create(['name' => 'ver profesores']);
        $Permission2 = Permission::create(['name' => 'eliminar profesores']);

        // Crear rol y asignar permisos
        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo([$Permission1, $Permission2]);

        // Asignar rol a un usuario
        // $user = User::find(1);
        $user->assignRole('admin');


        // estudiantes 

        // Crear el rol de estudiante si no existe
        $roleEstudiante = Role::firstOrCreate(['name' => 'estudiante']);

        // Crear 6 grados (1° a 6°)
        Grado::factory()->count(6)->create();

        // Crear 3 grupos (A, B, C)
        Grupo::factory()->count(3)->create();

        Materia::factory()->count(3)->create();

        // Crear 30 usuarios y estudiantes
        User::factory()
            ->count(30)
            ->create()
            ->each(function ($user) use ($roleEstudiante) {
                $user->assignRole($roleEstudiante); // asignar rol

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
    }
}
