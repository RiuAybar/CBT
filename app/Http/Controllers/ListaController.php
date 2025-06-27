<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Lista;
use App\Models\Estudiante;
use App\Models\Asistencia;
use App\Models\EscalaEvaluativa;
use App\Models\Evaluacion;
use App\Models\MateriaParcialEscala;
use App\Models\NotasPorAspecto;
use App\Models\Parcial;
use App\Models\Seguimiento;
use App\Models\Semestre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Type\Integer;
use Spatie\Permission\Models\Role;

class ListaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // dd($request);
        $search = $request->query('search');

        // Obtener todas las fechas únicas de asistencias ordenadas y formateadas
        $fechas = Asistencia::select('date')
            ->distinct()
            ->get()
            ->pluck('date')
            ->unique()
            ->sort()
            ->map(fn($date) => \Carbon\Carbon::parse($date)->format('Y-m-d'))
            ->values()
            ->toArray();

        // Query con relaciones y filtro por asistencias de fechas obtenidas
        $query = Estudiante::with([
            'user',
            'grado',
            'grupo',
            'asistencia' => function ($q) use ($fechas) {
                $q->whereIn('date', $fechas);
            }
        ]);

        // Aplicar filtro de búsqueda si existe
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($sub) use ($search) {
                    $sub->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                })->orWhereHas('grupo', function ($sub) use ($search) {
                    $sub->where('nombre', 'like', '%' . $search . '%');
                });
            });
        }

        // Obtener estudiantes y mapear asistencias por fecha
        $estudiantes = $query->get()->map(function ($estudiante) use ($fechas) {
            $asistencias = $estudiante->asistencia->pluck('status', 'date')->toArray();

            // Construir un array con cada fecha
            $asistenciaPorFecha = [];
            foreach ($fechas as $fecha) {
                $asistenciaPorFecha[$fecha] = $asistencias[$fecha] ?? null;
            }

            return array_merge([
                'id' => $estudiante->id,
                'nombre' => $estudiante->user->name,
                'email' => $estudiante->user->email,
                'grado' => $estudiante->grado->nombre,
                'grupo' => $estudiante->grupo->nombre,
            ], $asistenciaPorFecha);
        });

        // Retornar respuesta JSON
        return response()->json([
            'columns' => array_merge(
                ['id', 'nombre', 'email', 'grado', 'grupo'],
                $fechas
            ),
            'data' => $estudiantes,
        ]);
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     */
    public function show(Request $request, int $listas_id)
    {
        $search = $request->query('search');

        $query = User::role('estudiante')
            ->join('listas', 'users.id', '=', 'listas.alumno_id')
            ->join('seguimientos', 'listas.seguimiento_id', '=', 'seguimientos.id')
            ->where('listas.seguimiento_id', $listas_id);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%$search%");
            })->limit(5);
        }

        return response()->json($query->select('users.id', 'users.name')->get(), 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {}
    public function Seguimiento(int $seguimiento_id)
    {
        $Parcial = Parcial::first(['id as id', 'nombre as text']);
        // dd($seguimiento_id);
        $Seguimiento = Seguimiento::
            // join('seguimientos', 'listas.seguimiento_id', '=', 'seguimientos.id')
            join('materias', 'seguimientos.materia_id', '=', 'materias.id')
            ->join('semestres', 'seguimientos.semestre_id', '=', 'semestres.id')
            ->join('grupos', 'seguimientos.grupo_id', '=', 'grupos.id')
            ->join('grados', 'grupos.grado_id', '=', 'grados.id')
            ->join('carreras', 'seguimientos.carrera_id', '=', 'carreras.id')
            ->join('users as profesores', 'seguimientos.profesor_id', '=', 'profesores.id')
            ->where('seguimientos.id', $seguimiento_id)
            ->select(
                'seguimientos.id as seguimiento_id',
                // 'listas.alumno_id',
                // 'listas.created_at as fecha_lista',
                'materias.nombre as materia',
                'semestres.nombre as semestre',
                // 'grupos.nombre as grupo',
                DB::raw("CONCAT(grados.nombre, ' - ', grupos.nombre) as grupo"),
                'carreras.nombre as carrera',
                'profesores.name as profesor',
                'seguimientos.ano as año'
            )
            ->first();
        return response()->json([
            'Seguimiento' => $Seguimiento,
            'Parcial' => $Parcial
        ], 200);
    }

    // User
    public function UserDisponibles(int $seguimiento_id, Request $request)
    {
        $search = trim($request->query('search'));

        // Si no hay búsqueda, devolver vacío
        if (empty($search)) {
            return response()->json([], 200);
        }

        // Obtener IDs de alumnos ya asignados
        $usuariosAsignados = Seguimiento::find($seguimiento_id)?->Lista()
            ->pluck('alumno_id')
            ->toArray() ?? [];

        // Buscar estudiantes no asignados que coincidan con el texto
        $data = User::role('estudiante')
            ->whereNotIn('id', $usuariosAsignados)
            ->where('name', 'like', '%' . $search . '%')
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get(['id', 'name']);

        return response()->json($data, 200);
    }

    // asignarUsuario
    public function asignarUsuario(Request $request, Seguimiento $Seguimiento)
    {
        try {
            DB::beginTransaction();
            // Validar solo el alumno, el seguimiento ya está ligado
            $request->validate([
                'alumno_id' => 'required|exists:users,id',
            ]);
            // Crear el registro en la tabla `listas`
            Lista::create([
                'alumno_id' => $request->alumno_id,
                'seguimiento_id' => $Seguimiento->id,
            ]);
            DB::commit();
            return response()->json(['message' => 'Usuario asignado correctamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            // return response()->json($e->getMessage(), 500);
            return response()->json("No se asignó el usuario, consulte al administrador", 500);
        }
    }
    public function buscarParciales(Request $request, int $seguimiento_id)
    {
        $search = trim($request->query('search'));
        // Si no hay búsqueda, devolver vacío
        if (empty($search)) {
            return response()->json([], 200);
        }
        // Buscar estudiantes no asignados que coincidan con el texto
        $data = Parcial::where('nombre', 'like', '%' . $search . '%')
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get(['id', 'nombre']);

        return response()->json($data, 200);
    }
    // buscarEscalas
    public function buscarEscalas(Request $request, Seguimiento $Seguimiento)
    {
        $search = trim($request->query('search'));
        $parcial_id = trim($request->query('parcial_id'));

        // Si no hay término de búsqueda, devolver un array vacío
        if (empty($search)) {
            return response()->json([], 200);
        }

        // Obtener IDs de escalas ya asignadas
        $escalaAsignados = MateriaParcialEscala::where('parcial_id', $parcial_id)
            ->where('materia_id', $Seguimiento->materia_id)
            ->pluck('escala_evaluativa_id')
            ->toArray() ?? [];

        // Construir la consulta para buscar escalas no asignadas que coincidan con el texto
        $query = EscalaEvaluativa::whereNotIn('id', $escalaAsignados);

        // Aplicar filtros de búsqueda si hay un término de búsqueda
        $query->where(function ($q) use ($search) {
            $q->where('nombre', 'like', '%' . $search . '%')
                ->orWhere('abreviatura', 'like', '%' . $search . '%');
        });

        // Aplicar orden, límite y ejecutar la consulta, asignando los resultados a $data
        $data = $query->orderBy('id', 'desc')
            ->limit(5)
            ->get(['id', 'nombre']); // <-- Asigna los resultados de get() de vuelta a $data

        return response()->json($data, 200);
    }
    // buscarEscalasAsignadas
    public function buscarEscalasAsignadas(Request $request, Seguimiento $Seguimiento)
    {
        $search = trim($request->query('search'));
        $parcial_id = intval($request->query('parcial_id'));

        $query = MateriaParcialEscala::join('escalas_evaluativas as ee', 'materia_parcial_escala.escala_evaluativa_id', '=', 'ee.id')
            ->where('materia_parcial_escala.materia_id', $Seguimiento->materia_id)
            ->where('materia_parcial_escala.parcial_id', $parcial_id);
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ee.nombre', 'like', '%' . $search . '%')
                    ->orWhere('ee.abreviatura', 'like', '%' . $search . '%');
            });
        }
        $data = $query->orderBy('ee.id', 'desc')
            ->distinct()
            ->select('ee.id', 'materia_parcial_escala.id as materiaParcialEscalaId', 'ee.nombre', 'ee.abreviatura')
            ->limit(5)
            ->get();

        return response()->json($data, 200);
    }

    // asignarEscala
    public function asignarEscala(Request $request, Seguimiento $Seguimiento)
    {
        try {
            DB::beginTransaction();
            // Validar solo el alumno, el seguimiento ya está ligado
            $request->validate([
                'escala_evaluativa_id' => 'required|exists:escalas_evaluativas,id',
                'parcial_id' => 'required|exists:users,id',
            ]);
            // Crear el registro en la tabla `MateriaParcialEscala`
            MateriaParcialEscala::create([
                'escala_evaluativa_id' => $request->escala_evaluativa_id,
                'parcial_id' => $request->parcial_id,
                'materia_id' => $Seguimiento->materia_id,
            ]);
            DB::commit();
            return response()->json(['message' => 'Usuario asignado correctamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 500);
            return response()->json("No se asignó el usuario, consulte al administrador", 500);
        }
    }

    public function evaluacion(Request $request, Seguimiento $Seguimiento)
    {
        $parcial_id = $request->query('parcial_id');
        if (!$parcial_id) {
            return response()->json(['error' => 'Parcial no especificado'], 422);
        }

        // 1. Obtener escalas evaluativas asociadas a esta materia + parcial
        $escalas = MateriaParcialEscala::where('materia_id', $Seguimiento->materia_id)
            ->where('parcial_id', $parcial_id)
            ->with('escala')
            ->get()
            ->pluck('escala');

        // 2. Obtener todos los alumnos del seguimiento
        $listas = DB::table('listas as l')
            ->join('users as u', 'u.id', '=', 'l.alumno_id')
            ->where('l.seguimiento_id', $Seguimiento->id)
            ->select('l.id as lista_id', 'u.name as alumno')
            ->get();

        // 3. Obtener todas las evaluaciones de este parcial
        $evaluaciones = Evaluacion::where('parcial_id', $parcial_id)
            ->whereIn('lista_id', $listas->pluck('lista_id'))
            ->get()
            ->keyBy('lista_id');

        // 4. Obtener notas por aspecto de todas las evaluaciones
        $notas = DB::table('notas_por_aspectos as npa')
            ->join('escalas_evaluativas as ee', 'ee.id', '=', 'npa.escala_evaluativa_id')
            ->whereIn('npa.evaluacion_id', $evaluaciones->pluck('id'))
            ->select(
                'npa.evaluacion_id',
                'ee.abreviatura',
                'npa.cantidad_obtenida',
                'npa.puntaje_obtenido'
            )
            ->get();

        // 5. Organizar notas por evaluación
        $notasPorEvaluacion = [];
        foreach ($notas as $nota) {
            $evaluacionId = $nota->evaluacion_id;
            $abreviatura = $nota->abreviatura;

            if (!isset($notasPorEvaluacion[$evaluacionId])) {
                $notasPorEvaluacion[$evaluacionId] = [
                    'cantidades' => [],
                    'puntajes' => [],
                ];
            }

            $notasPorEvaluacion[$evaluacionId]['cantidades'][$abreviatura] = $nota->cantidad_obtenida;
            $notasPorEvaluacion[$evaluacionId]['puntajes'][$abreviatura] = $nota->puntaje_obtenido;
        }

        // Si hay parámetro de búsqueda, filtra los alumnos
        if ($request->filled('busqueda')) {
            $busqueda = strtolower($request->busqueda);

            $listas = $listas->filter(function ($item) use ($busqueda) {
                return str_contains(strtolower($item->alumno), $busqueda);
            });

            // Importante: volver a indexar si se usa ->pluck() después
            $listas = $listas->values();
        }

        // Obtener todas las abreviaturas de las escalas
        $abreviaturas = $escalas->pluck('abreviatura');
        // 6. Armar la respuesta
        $alumnos = [];

        foreach ($listas as $lista) {
            $evaluacion = $evaluaciones->get($lista->lista_id);
            $notasEval = $evaluacion ? $notasPorEvaluacion[$evaluacion->id] ?? [] : [];

            // Inicializar con todas las escalas
            $cantidadesCompletas = [];
            $puntajesCompletas = [];

            foreach ($abreviaturas as $abrev) {
                $cantidadesCompletas[$abrev] = $notasEval['cantidades'][$abrev] ?? null;
                $puntajesCompletas[$abrev] = $notasEval['puntajes'][$abrev] ?? null;
            }

            $alumnos[] = [
                'lista_id' => $lista->lista_id,
                'nombre' => $lista->alumno,
                'faltas' => $evaluacion->faltas ?? null,
                'suma' => $evaluacion->suma ?? null,
                'calificacion' => $evaluacion->calificacion_parcial ?? null,
                'cantidades' => (object)$cantidadesCompletas,
                'puntajes' => (object)$puntajesCompletas,
            ];
        }

        return response()->json([
            'escalas' => $escalas->values(),
            'alumnos' => $alumnos,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function EliminarEscala(MateriaParcialEscala $MateriaParcialEscala)
    {
        try {
            DB::beginTransaction();
            $MateriaParcialEscala->delete();
            DB::commit();
            return response()->json('Exito', 204);
        } catch (\Exception $e) {
            //throw $th;
            DB::rollBack();
            return response()->json($e->getMessage(), 500);
        }
    }

    public function guardarNotasPorAspecto(Request $request, int $id)
    {
        try {
            DB::beginTransaction();
            $parcialId = $request->input('parcial_id');
            $listaId = $request->input('lista_id');
            $items = $request->input('items');
            $name = $request->input('name');
            $value = $request->input('value');
            $columnasDef = in_array($name, ['faltas', 'suma', 'calificacion_parcial']) ? $name : null;

            $evaluacion = Evaluacion::firstOrCreate([
                'lista_id' => $listaId,
                'parcial_id' => $parcialId,
            ]);
            if ($columnasDef) {
                $evaluacion->update([
                    $columnasDef => $value,
                ]);
            }

            foreach ($items as $item) {
                // $listaId = $item['lista_id'];


                $escala = EscalaEvaluativa::where('abreviatura', $item['escala_abreviatura'])->first();
                if ($escala) {
                    NotasPorAspecto::updateOrCreate(
                        [
                            'evaluacion_id' => $evaluacion->id,
                            'escala_evaluativa_id' => $escala->id,
                        ],
                        [
                            'cantidad_obtenida' => (float)$item['cantidad_obtenida'],
                            'puntaje_obtenido' => (float)$item['puntaje_obtenido'],
                        ]
                    );
                }
            }
            DB::commit();
            return response()->json('Exito', 204);
        } catch (\Exception $e) {
            //throw $th;
            DB::rollBack();
            return response()->json($e->getMessage(), 500);
        }
    }
}
