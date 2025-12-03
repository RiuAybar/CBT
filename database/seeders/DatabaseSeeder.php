<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Grado;
use App\Models\Grupo;
use App\Models\Image;
use App\Models\Lista;
use App\Models\Carrera;
use App\Models\Escuela;
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
use App\Models\SeguimientoHorario;
use Illuminate\Support\Facades\DB;
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



        if (config('app.debug')) {
            // Asegúrate de tener profesores y materias creadas
            $seguimientos = Seguimiento::factory()->count(10)->create();
            // ↓↓↓ AGREGAR ESTO ↓↓↓
            // Crear listas para cada seguimiento

            // Crear listas para cada seguimiento
            foreach ($seguimientos as $seguimiento) {


                // Horario básico: Lunes y Miércoles
                $horariosBasicos = [
                    ['dia' => 'LUNES', 'hora' => '08:00', 'duracion' => 2],
                    ['dia' => 'MIÉRCOLES', 'hora' => '10:00', 'duracion' => 2],
                    ['dia' => 'VIERNES', 'hora' => '14:00', 'duracion' => 1],
                ];

                foreach ($horariosBasicos as $horario) {
                    $horaInicio = $horario['hora'];
                    $horaFin = date('H:i', strtotime($horaInicio) + ($horario['duracion'] * 3600));

                    SeguimientoHorario::firstOrCreate([
                        'seguimiento_id' => $seguimiento->id,
                        'dia' => $horario['dia'],
                        'hora_inicio' => $horaInicio,
                    ], [
                        'hora_fin' => $horaFin,
                    ]);
                }

                $estudiantes = Estudiante::inRandomOrder()
                    ->take(rand(5, 15))
                    ->get();

                foreach ($estudiantes as $index => $estudiante) {
                    Lista::create([
                        'seguimiento_id' => $seguimiento->id,
                        'alumno_id' => $estudiante->user_id,
                        'listaNumero' => $index + 1,
                        'estatus' => 'Alta',
                    ]);
                }
            }
            // Asegurar que cada materia-parcial tenga escalas asignadas
            $materias = Materia::all();
            $parciales = Parcial::all();
            $escalas = EscalaEvaluativa::all();

            foreach ($materias as $materia) {
                foreach ($parciales as $parcial) {
                    // Verificar si ya tiene escalas
                    $count = DB::table('materia_parcial_escala')
                        ->where('materia_id', $materia->id)
                        ->where('parcial_id', $parcial->id)
                        ->count();

                    if ($count === 0) {
                        // Asignar 2-4 escalas aleatorias
                        $escalasSeleccionadas = $escalas->random(rand(2, 4));

                        foreach ($escalasSeleccionadas as $escala) {
                            DB::table('materia_parcial_escala')->insert([
                                'materia_id' => $materia->id,
                                'parcial_id' => $parcial->id,
                                'escala_evaluativa_id' => $escala->id,
                                'valor_maximo' => fake()->randomFloat(2, 5, 20),
                                'porcentaje' => fake()->randomFloat(2, 10, 40),
                                'activo' => 1,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            }

            // Crear evaluaciones para cada lista en cada parcial
            $listas = Lista::all();
            $parcialesIds = Parcial::pluck('id')->toArray();

            foreach ($listas as $lista) {
                foreach ($parcialesIds as $parcialId) {
                    // Crear evaluación (como hace tu función guardarNotasPorAspecto)
                    $evaluacion = Evaluacion::firstOrCreate([
                        'lista_id' => $lista->id,
                        'parcial_id' => $parcialId,
                    ], [
                        'faltas' => fake()->numberBetween(0, 5),
                        'suma' => fake()->randomFloat(2, 0, 100),
                        'calificacion_parcial' => fake()->randomFloat(2, 0, 10),
                    ]);

                    // Obtener escalas asignadas a esta materia-parcial
                    $seguimiento = Seguimiento::find($lista->seguimiento_id);
                    if (!$seguimiento) continue;

                    $escalasAsignadas = DB::table('materia_parcial_escala')
                        ->where('materia_id', $seguimiento->materia_id)
                        ->where('parcial_id', $parcialId)
                        ->get();

                    // Crear notas para algunas escalas asignadas
                    if ($escalasAsignadas->count() > 0) {
                        $cantidadNotas = min(rand(1, 3), $escalasAsignadas->count());
                        $escalasSeleccionadas = $escalasAsignadas->random($cantidadNotas);

                        foreach ($escalasSeleccionadas as $escalaAsignada) {
                            // Verificar si ya existe
                            $existe = NotasPorAspecto::where('evaluacion_id', $evaluacion->id)
                                ->where('escala_evaluativa_id', $escalaAsignada->escala_evaluativa_id)
                                ->exists();

                            if (!$existe) {
                                NotasPorAspecto::create([
                                    'evaluacion_id' => $evaluacion->id,
                                    'escala_evaluativa_id' => $escalaAsignada->escala_evaluativa_id,
                                    'cantidad_obtenida' => fake()->randomFloat(2, 0, 10),
                                    'puntaje_obtenido' => fake()->randomFloat(2, 0, 100),
                                ]);
                            }
                        }
                    }
                }
            }

            // Si estás en desarrollo, crear más datos de prueba
            $this->seedRegistroHorasDocenciaTest();
        }

        // foreach ($seguimientos as $seguimiento) {
        //     // Seleccionar algunos estudiantes aleatorios para esta lista
        //     $estudiantes = Estudiante::inRandomOrder()
        //         ->take(rand(5, 15)) // 5-15 estudiantes por lista
        //         ->get();

        //     foreach ($estudiantes as $index => $estudiante) {
        //         Lista::create([
        //             'seguimiento_id' => $seguimiento->id,
        //             'alumno_id' => $estudiante->user_id, // O $estudiante->id según tu relación
        //             'listaNumero' => $index + 1,
        //             'estatus' => 'Alta', // o random entre 'Alta', 'Baja'
        //         ]);
        //     }
        // }
        // ↑↑↑ AGREGAR ESTO ↑↑↑

        //Crear 10 evaluaciones
        // Evaluacion::factory()->count(10)->create();

        // // ↓↓↓ AGREGAR ESTO: Crear notas por aspecto ↓↓↓
        // $evaluaciones = Evaluacion::all();
        // $escalas = EscalaEvaluativa::all();
        // foreach ($evaluaciones as $evaluacion) {
        //     // Para cada evaluación, crear 2-4 notas por aspecto
        //     $escalasAleatorias = $escalas->random(rand(2, 4));

        //     foreach ($escalasAleatorias as $escala) {
        //         NotasPorAspecto::create([
        //             'evaluacion_id' => $evaluacion->id,
        //             'escala_evaluativa_id' => $escala->id,
        //             'cantidad_obtenida' => fake()->randomFloat(2, 0, 10),
        //             'puntaje_obtenido' => fake()->randomFloat(2, 0, 100),
        //         ]);
        //     }
        // }
        // foreach ($evaluaciones as $evaluacion) {
        //     // 1. Obtener la lista y el seguimiento relacionado
        //     $lista = Lista::find($evaluacion->lista_id);

        //     if (!$lista) {
        //         continue;
        //     }

        //     $seguimiento = Seguimiento::find($lista->seguimiento_id);

        //     if (!$seguimiento) {
        //         continue;
        //     }

        //     // 2. Obtener las escalas asignadas a ESTA materia-parcial específica
        //     $escalasAsignadas = DB::table('materia_parcial_escala')
        //         ->where('materia_id', $seguimiento->materia_id)
        //         ->where('parcial_id', $evaluacion->parcial_id)
        //         ->get();

        //     if ($escalasAsignadas->count() === 0) {
        //         continue; // No hay escalas asignadas para esta combinación
        //     }

        //     // 3. Crear notas solo para algunas de las escalas asignadas
        //     $cantidadNotas = min(rand(2, 4), $escalasAsignadas->count());
        //     $escalasSeleccionadas = $escalasAsignadas->random($cantidadNotas);

        //     foreach ($escalasSeleccionadas as $escalaAsignada) {
        //         NotasPorAspecto::create([
        //             'evaluacion_id' => $evaluacion->id,
        //             'escala_evaluativa_id' => $escalaAsignada->escala_evaluativa_id,
        //             'cantidad_obtenida' => fake()->randomFloat(2, 0, 10),
        //             'puntaje_obtenido' => fake()->randomFloat(2, 0, 100),
        //         ]);
        //     }
        // }

        // SeguimientoHorario::factory()->count(5)->create();

        // $materias = Materia::all();
        // $parciales = Parcial::all();
        // $escalas = EscalaEvaluativa::all();

        // foreach ($materias as $materia) {
        //     foreach ($parciales as $parcial) {
        //         // Asignar 2-4 escalas aleatorias a cada materia+parcial
        //         $materia->escalasPorParcial()->attach(
        //             $escalas->random(rand(2, 4))->pluck('id')->toArray(),
        //             ['parcial_id' => $parcial->id]
        //         );
        //     }
        // }
        // foreach ($materias as $materia) {
        //     foreach ($parciales as $parcial) {
        //         // Seleccionar escalas aleatorias para esta combinación
        //         $escalasSeleccionadas = $escalas->random(rand(2, 4));

        //         foreach ($escalasSeleccionadas as $escala) {
        //             DB::table('materia_parcial_escala')->insert([
        //                 'materia_id' => $materia->id,
        //                 'parcial_id' => $parcial->id,
        //                 'escala_evaluativa_id' => $escala->id,
        //                 'valor_maximo' => fake()->randomFloat(2, 5, 20), // Ej: 10, 15, 20
        //                 'porcentaje' => fake()->randomFloat(2, 10, 40), // Ej: 20%, 30%, 40%
        //                 'activo' => 1,
        //                 'created_at' => now(),
        //                 'updated_at' => now(),
        //             ]);
        //         }
        //     }
        // }
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
            // ['name' => 'ver calificaciones', 'guard_name' => 'api'],
        ];
    }

    public function permisosProfesor()
    {
        return $this->mergePermissions(
            [
                // ['name' => 'ver calificaciones', 'guard_name' => 'api'],
                // ['name' => 'actualizar calificaciones', 'guard_name' => 'api'],

                // http://localhost:8000/lista/1/edit
                ['name' => 'ver listas', 'guard_name' => 'api'],
                ['name' => 'puede agregar alumno listas', 'guard_name' => 'api'],
                ['name' => 'ver parciales en lista', 'guard_name' => 'api'],
                ['name' => 'ver escalas asignadas en lista', 'guard_name' => 'api'],
                ['name' => 'puede agregar escala en lista', 'guard_name' => 'api'],
                ['name' => 'puede eliminar materia parcial escala', 'guard_name' => 'api'],
                
                // http://localhost:8000/escalaevaluativa
                ['name' => 'ver escalas evaluativas', 'guard_name' => 'api'],
                ['name' => 'puede agregar escalas evaluativas', 'guard_name' => 'api'],
                ['name' => 'puede editar escalas evaluativas', 'guard_name' => 'api'],
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
                ['name' => 'puede dar de baja a un estudiante', 'guard_name' => 'api'],

                // http://localhost:8000/horasseguimiento/1/agregar
                ['name' => 'ver horas seguimiento', 'guard_name' => 'api'],
                ['name' => 'puede agregar horas seguimiento', 'guard_name' => 'api'],
                ['name' => 'puede editar horas seguimiento', 'guard_name' => 'api'],
                ['name' => 'puede eliminar horas seguimiento', 'guard_name' => 'api'],

                // http://localhost:8000/escuela
                ['name' => 'ver datos de mi escuela', 'guard_name' => 'api'],
                ['name' => 'puede editar datos de mi escuela', 'guard_name' => 'api'],


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
            $this->permisosProfesor(),
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
    private function seedRegistroHorasDocenciaTest(): void
    {
        // Crear registros más completos para pruebas
        $carreras = Carrera::all();
        $materias = Materia::all();
        $meses = [
            'Enero',
            'Febrero',
            'Marzo',
            'Abril',
            'Mayo',
            'Junio',
            'Julio',
            'Agosto',
            'Septiembre',
            'Octubre',
            'Noviembre',
            'Diciembre'
        ];

        // Limitar cantidad para no crear demasiados registros
        $maxRegistros = 20;
        $creados = 0;

        while ($creados < $maxRegistros && $creados < (count($carreras) * count($materias) * 3)) {
            $carrera = $carreras->random();
            $materia = $materias->random();
            $mes = $meses[array_rand($meses)];

            // Verificar si ya existe
            $existe = RegistroHorasDocencia::where('mes', $mes)
                ->where('carrera_id', $carrera->id)
                ->where('materia_id', $materia->id)
                ->exists();

            if (!$existe) {
                RegistroHorasDocencia::create([
                    'mes' => $mes,
                    'horasImpartidas' => rand(10, 40),
                    'carrera_id' => $carrera->id,
                    'materia_id' => $materia->id,
                ]);
                $creados++;
            }
        }
    }
}
