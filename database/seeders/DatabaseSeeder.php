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
use Illuminate\Database\Seeder;
use App\Models\EscalaEvaluativa;
use App\Models\Escuela;
use App\Models\SeguimientoHorario;
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
        // Crear 6 semestre (1° a 6°)
        Semestre::factory()->count(6)->create();

        // Crear 3 grados (1° a 3°)
        Grado::factory()->count(3)->create();
        $grados = Grado::all();
        // Crear 3 grupos (1 A, 2 B, 3 C)
        foreach ($grados as $grado) {
            Grupo::factory()->count(2)->create([
                'grado_id' => $grado->id, // ejemplo: 1A y 1B, etc.
            ]);
        }

        // Registro Horas Docencia
        RegistroHorasDocencia::factory()->count(6)->create();

        //Crear 10 carreras
        Carrera::factory()->count(10)->create();
        //Crear 3 materias(Asignaturas)
        Materia::factory()->count(3)->create();
        //Crear 3 parciales(Parciales)
        Parcial::factory()->count(3)->create();

        $crearEscalas = $this->Escalas();
        foreach ($crearEscalas as $escala) {
            $EscalasSeleccionadas[] = EscalaEvaluativa::create($escala);
        }

        $crearRoles = $this->Roles();
        // Crear el rol de estudiante si no existe
        foreach ($crearRoles as $value) {
            Role::firstOrCreate($value);
        }

        $permisosEstudiante = $this->permisosEstudiante();
        $permisosEstudianteAsignados = [];
        // Crear el rol de estudiante si no existe
        foreach ($permisosEstudiante as $value) {
            $permisosEstudianteAsignados[] = Permission::firstOrCreate($value);
        }
        $rolEstudiante = Role::where('name', 'estudiante')->first();
        $rolEstudiante->givePermissionTo($permisosEstudianteAsignados);

        $permisosProfesor = $this->permisosProfesor();
        $permisosProfesorAsignados = [];
        // Crear el rol de orientador si no existe
        foreach ($permisosProfesor as $value) {
            $permisosProfesorAsignados[] = Permission::firstOrCreate($value);
        }
        $rolProfesores = Role::where('name', 'profesor')->first();
        $rolProfesores->givePermissionTo($permisosProfesorAsignados);


        $permisosOrientador = $this->permisosOrientador();
        $permisosOrientadorAsignados = [];
        // Crear el rol de orientador si no existe
        foreach ($permisosOrientador as $value) {
            $permisosOrientadorAsignados[] = Permission::firstOrCreate($value);
        }
        $rolOrientador = Role::where('name', 'orientador')->first();
        $rolOrientador->givePermissionTo($permisosOrientadorAsignados);

        $permisosAdministrador = $this->permisosAdministrador();
        $permisosAdministradorAsignados = [];
        // Crear el rol de orientador si no existe
        foreach ($permisosAdministrador as $value) {
            $permisosAdministradorAsignados[] = Permission::firstOrCreate($value);
        }
        $rolAdministrador = Role::where('name', 'admin')->first();
        $rolAdministrador->givePermissionTo($permisosAdministradorAsignados);

        // Crear 30 estudiantes
        User::factory()
            ->count(30)
            ->create()
            ->each(function ($user) use ($rolEstudiante) {
                $user->assignRole($rolEstudiante); // asignar rol

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

        //Crear 3 profesores
        User::factory()
            ->count(3)
            ->create()
            ->each(function ($user) use ($rolProfesores) {
                $user->assignRole($rolProfesores); // asignar rol
            });
        //Crear 3 orientadores
        User::factory()
            ->count(3)
            ->create()
            ->each(function ($user) use ($rolOrientador) {
                $user->assignRole($rolOrientador); // asignar rol
            });

        //Crear un administrador
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'estatus' => 'habilitado',
            'password' => bcrypt('$Super001'),
        ]);
        $user->assignRole($rolAdministrador); // asignar rol

        // Asegúrate de tener profesores y materias creadas
        Seguimiento::factory()->count(10)->create();
        //Crear 10 evaluaciones
        Evaluacion::factory()->count(10)->create();

        SeguimientoHorario::factory()->count(5)->create();

        $materias = Materia::all();
        $parciales = Parcial::all();
        $escalas = EscalaEvaluativa::all();

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

        Escuela::create([
            'nombre_escuela' => 'CBT, AMANALCO DE BECERRA',
            'direccion' => 'GENERAL DE EDUCACIÓN MEDIA SUPERIOR',
            'departamento' => 'DE BACHILLERATO TECNOLÓGICO',
            'turno' => 'DISCONTINUO',
            'nivel' => 'MEDIO SUPERIOR TÉCNICO',
            'clave_trabajo' => '15ECT0112M',
            'numero_cct' => 'NA',
            'zona_escolar' => 'BT009',
            'domicilio' => 'DOMICILIO CONOCIDO S/N',
            'localidad_colonia' => 'EL POTRERO',
            'municipio' => 'AMANALCO',
            'telefono' => '7228353322',
            'docente' => 'LÓPEZ SANTANA SEBASTIAN',
            'subdirector_escolar' => 'MARÍA DEL CARMEN ASSENETH VELÁZQUEZ LÓPEZ',
            'director_escolar' => 'GADIEL RECILLAS MIRANDA',
            'secretario_escolar' => 'LUIS GONZÁLEZ CALIXTO',
        ]);
    }

    public function Roles()
    {
        return [
            // ['name' => 'superuser', 'guard_name' => 'api'],
            ['name' => 'admin', 'guard_name' => 'api'],
            ['name' => 'orientador', 'guard_name' => 'api'],
            ['name' => 'profesor', 'guard_name' => 'api'],
            ['name' => 'estudiante', 'guard_name' => 'api'],

        ];
    }

    public function permisosEstudiante()
    {
        return [
            ['name' => 'ver calificaciones', 'guard_name' => 'api'],
        ];
    }

    public function permisosProfesor()
    {
        return $this->mergePermissions(
            [
                ['name' => 'ver calificaciones', 'guard_name' => 'api'],
                ['name' => 'actualizar calificaciones', 'guard_name' => 'api'],
            ]
        );
    }

    public function permisosOrientador()
    {
        return $this->mergePermissions(
            [
                // http://localhost:8000/users
                ['name' => 'ver usuarios', 'guard_name' => 'api'],
                // http://localhost:8000/grupos/1/edit
                ['name' => 'ver grados', 'guard_name' => 'api'],
                // http://localhost:8000/grados
                ['name' => 'ver grupos', 'guard_name' => 'api'],
                // http://localhost:8000/lista/1/edit
                ['name' => 'ver listas', 'guard_name' => 'api'],
                ['name' => 'puede agregar alumno listas', 'guard_name' => 'api'],
                ['name' => 'ver parciales en lista', 'guard_name' => 'api'],
                ['name' => 'ver escalas asignadas en lista', 'guard_name' => 'api'],
                ['name' => 'puede agregar escala en lista', 'guard_name' => 'api'],
                ['name' => 'puede eliminar materia parcial escala', 'guard_name' => 'api'],


                // http://localhost:8000/semestres
                ['name' => 'ver semestres', 'guard_name' => 'api'],
                // http://localhost:8000/materias
                ['name' => 'ver materias', 'guard_name' => 'api'],
                // http://localhost:8000/carreras
                ['name' => 'ver carreras', 'guard_name' => 'api'],
                ['name' => 'puede agregar carreras', 'guard_name' => 'api'],
                ['name' => 'puede editar carreras', 'guard_name' => 'api'],

                // http://localhost:8000/horasdocente
                ['name' => 'ver horas docente', 'guard_name' => 'api'],
                ['name' => 'puede agregar horas docente', 'guard_name' => 'api'],
                ['name' => 'puede editar horas docente', 'guard_name' => 'api'],

                // http://localhost:8000/parciales
                ['name' => 'ver parciales', 'guard_name' => 'api'],
                ['name' => 'puede agregar parciales', 'guard_name' => 'api'],
                ['name' => 'puede editar parciales', 'guard_name' => 'api'],

                // http://localhost:8000/seguimiento
                ['name' => 'ver seguimiento', 'guard_name' => 'api'],
                ['name' => 'puede crear seguimientos', 'guard_name' => 'api'],
                ['name' => 'puede editar seguimientos', 'guard_name' => 'api'],

                // http://localhost:8000/seguimiento
                ['name' => 'ver f1', 'guard_name' => 'api'],


                // http://localhost:8000/users
                ['name' => 'Puede ver estudiantes', 'guard_name' => 'api'],
                ['name' => 'Puede ver profesores', 'guard_name' => 'api'],
                // http://localhost:8000/users
                ['name' => 'Puede agregar estudiantes', 'guard_name' => 'api'],
                ['name' => 'Puede agregar profesores', 'guard_name' => 'api'],
                // http://localhost:8000/grados
                ['name' => 'Puede agregar grados', 'guard_name' => 'api'],
                ['name' => 'Puede editar grados', 'guard_name' => 'api'],
                // http://localhost:8000/grupos/1/edit
                ['name' => 'puede agregar grupos', 'guard_name' => 'api'],
                ['name' => 'puede editar grupos', 'guard_name' => 'api'],
                // http://localhost:8000/materias
                ['name' => 'puede agregar materias', 'guard_name' => 'api'],
                ['name' => 'puede editar materias', 'guard_name' => 'api'],
                // http://localhost:8000/semestres
                ['name' => 'puede agregar semestres', 'guard_name' => 'api'],
                ['name' => 'puede editar semestres', 'guard_name' => 'api'],

            ],
            $this->permisosEstudiante(),
        );
    }

    public function permisosAdministrador()
    {
        return $this->mergePermissions(
            [
                // http://localhost:8000/permisos
                ['name' => 'ver permisos', 'guard_name' => 'api'],
                // http://localhost:8000/roles
                ['name' => 'ver roles', 'guard_name' => 'api'],
                // http://localhost:8000/roles/1/edit
                ['name' => 'ver permisos asignados a roles', 'guard_name' => 'api'],

                // http://localhost:8000/users
                ['name' => 'Puede ver orientadores', 'guard_name' => 'api'],
                ['name' => 'Puede ver administradores', 'guard_name' => 'api'],

                //http://localhost:8000/users
                ['name' => 'Puede agregar orientadores', 'guard_name' => 'api'],
                ['name' => 'Puede agregar administradores', 'guard_name' => 'api'],

                // http://localhost:8000/users
                ['name' => 'puede editar estudiantes', 'guard_name' => 'api'],
                ['name' => 'puede editar profesores', 'guard_name' => 'api'],
                ['name' => 'puede editar orientadores', 'guard_name' => 'api'],
                ['name' => 'puede editar administradores', 'guard_name' => 'api'],
                
                // http://localhost:8000/users
                ['name' => 'puede desabilitar estudiantes', 'guard_name' => 'api'],
                ['name' => 'puede desabilitar profesores', 'guard_name' => 'api'],
                ['name' => 'puede desabilitar orientadores', 'guard_name' => 'api'],
                ['name' => 'puede desabilitar administradores', 'guard_name' => 'api'],




            ],
            $this->permisosOrientador()
        );
    }
    public function mergePermissions(...$lists)
    {
        $merged = [];

        foreach ($lists as $list) {
            foreach ($list as $permiso) {
                $merged[$permiso['name']] = $permiso; // evita duplicados por 'name'
            }
        }

        return array_values($merged);
    }

    public function Escalas()
    {
        return [
            ['nombre' => 'Examen', 'abreviatura' => 'Exa'],
            ['nombre' => 'Actividad', 'abreviatura' => 'Act'],
            ['nombre' => 'Trabajo en Clase', 'abreviatura' => 'T.C.'],
            ['nombre' => 'Trabajo en Equipo', 'abreviatura' => 'T.E.'],
        ];
    }

    // public function run(): void
    // {
    //     // User::factory(10)->create();
    //     $user = User::factory()->create([
    //         'name' => 'Test User',
    //         'email' => 'test@example.com',
    //         'estatus' => 'habilitado',
    //         'password' => bcrypt('$Super001'),
    //     ]);
    //     // Crear permisos
    //     $Permission1 = Permission::create(['name' => 'ver profesores', 'guard_name' => 'api']);
    //     $Permission2 = Permission::create(['name' => 'eliminar profesores', 'guard_name' => 'api']);

    //     // Crear rol y asignar permisos
    //     $role = Role::create(['name' => 'admin', 'guard_name' => 'api']);
    //     $role->givePermissionTo([$Permission1, $Permission2]);

    //     // Asignar rol a un usuario
    //     $user->assignRole('admin');

    //     //imagen
    //     // $user->image()->save(Image::factory()->create());

    //     Carrera::factory()->count(10)->create();

    //     // estudiantes 
    //     // Crear el rol de estudiante si no existe
    //     $roleEstudiante = Role::firstOrCreate(['name' => 'estudiante', 'guard_name' => 'api']);

    //     $roleOrientador = Role::firstOrCreate(['name' => 'orientador', 'guard_name' => 'api']);
    //     //Profesor
    //     Role::firstOrCreate(['name' => 'profesor', 'guard_name' => 'api']);
    //     $Profesor = User::create([
    //         'name' => 'Juan Pérez',
    //         'email' => 'juan@correo.com',
    //         'password' => bcrypt('secret'),
    //     ]);

    //     $Profesor->assignRole('profesor');

    //     // RegistroHorasDocencia::factory()->count(6)->create();

    //     Grado::factory()->count(3)->create();
    //     // Crear 6 grados (1° a 6°)
    //     $grados = Grado::all();

    //     // Crear 3 grupos (1 A, 2 B, 3 C)
    //     foreach ($grados as $grado) {
    //         Grupo::factory()->count(2)->create([
    //             'grado_id' => $grado->id, // ejemplo: 1A y 1B, etc.
    //         ]);
    //     }

    //     // Crear 6 semestre (1° a 6°)
    //     Semestre::factory()->count(6)->create();

    //     Parcial::factory()->count(3)->create();

    //     Materia::factory()->count(3)->create();




    //     // Crear 30 usuarios y estudiantes
    //     User::factory()
    //         ->count(30)
    //         ->create()
    //         ->each(function ($user) use ($roleEstudiante) {
    //             $user->assignRole($roleEstudiante); // asignar rol

    //             // $user->image()->save(Image::factory()->create());

    //             $estudiante = Estudiante::factory()->create([
    //                 'user_id' => $user->id,
    //             ]);

    //             $fechas = [];

    //             while (count($fechas) < 5) {
    //                 $fecha = fake()->dateTimeBetween('-1 month', 'now')->format('Y-m-d');

    //                 if (!in_array($fecha, $fechas)) {
    //                     $fechas[] = $fecha;

    //                     Asistencia::factory()->create([
    //                         'estudiante_id' => $estudiante->id,
    //                         'date' => $fecha,
    //                     ]);
    //                 }
    //             }
    //         });
    //     $escalas = [
    //         ['nombre' => 'Examen', 'abreviatura' => 'Exa'],
    //         ['nombre' => 'Actividad', 'abreviatura' => 'Act'],
    //         ['nombre' => 'Trabajo en Clase', 'abreviatura' => 'T.C.'],
    //         ['nombre' => 'Trabajo en Equipo', 'abreviatura' => 'T.E.'],
    //     ];
    //     foreach ($escalas as $escala) {
    //         EscalaEvaluativa::create($escala);
    //     }

    //     // Asegúrate de tener profesores y materias creadas
    //     Seguimiento::factory()->count(10)->create();

    //     Evaluacion::factory()->count(10)->create();

    //     // EscalaEvaluativa::factory()->count(4)->create();

    //     $materias = Materia::all();
    //     $parciales = Parcial::all();
    //     $escalas = EscalaEvaluativa::all();
    //     // dd($escalas);

    //     foreach ($materias as $materia) {
    //         foreach ($parciales as $parcial) {
    //             // Asignar 2-4 escalas aleatorias a cada materia+parcial
    //             $materia->escalasPorParcial()->attach(
    //                 $escalas->random(rand(2, 4))->pluck('id')->toArray(),
    //                 ['parcial_id' => $parcial->id]
    //             );
    //         }
    //     }

    //     // NotasPorAspecto::factory()->count(20)->create();
    // }
}
