<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\SeguimientoRequest;
use App\Models\{Carrera, Grupo, Lista, Materia, User, Seguimiento, Semestre,};

class SeguimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $query = Seguimiento::join('materias', 'seguimientos.materia_id', '=', 'materias.id')
            ->join('semestres', 'seguimientos.semestre_id', '=', 'semestres.id')
            ->join('grupos', 'seguimientos.grupo_id', '=', 'grupos.id')
            ->join('grados', 'grupos.grado_id', '=', 'grados.id')
            ->join('carreras', 'seguimientos.carrera_id', '=', 'carreras.id')
            ->join('users as profesores', 'seguimientos.profesor_id', '=', 'profesores.id')
            ->orderBy('seguimientos.id', 'desc')
            ->select([
                'seguimientos.id',
                'seguimientos.ano',
                'materias.id as materia_id',
                'materias.nombre as materias',
                'semestres.id as semestre_id',
                'semestres.nombre as semestres',
                'grupos.id as grupo_id',
                DB::raw("CONCAT(grados.nombre, ' - ', grupos.nombre) as grupos"),
                // 'grupos.nombre as grupos',
                'carreras.id as carrera_id',
                'carreras.nombre as carreras',
                'profesores.id as profesor_id',
                'profesores.name as profesores'
            ]);
        if ($request->ano) {
            $query->where('seguimientos.ano', $request->ano);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('materias.nombre', 'like', "%$search%")
                    ->orWhere('semestres.nombre', 'like', "%$search%")
                    ->orWhere('grupos.nombre', 'like', "%$search%")
                    ->orWhere('grados.nombre', 'like', "%$search%")
                    ->orWhere('carreras.nombre', 'like', "%$search%")
                    ->orWhere('profesores.name', 'like', "%$search%")
                    ->orWhereRaw("CONCAT(grados.nombre, ' - ', grupos.nombre) LIKE ?", ["%$search%"]);
            })->limit(5);
        }
        return response()->json($query->get(), 200);
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
    public function store(SeguimientoRequest $request)
    {
        try {
            DB::beginTransaction();
            Seguimiento::create($request->validated());
            DB::commit();
            return response()->json('Exito', 201);
        } catch (\Exception $e) {
            //Si hay un error / excepción en el código anterior antes de confirmar, se revertirá
            DB::rollBack();
            // return response()->json($e->getMessage(),500);
            return response()->json("No se creo el registro, consulte al administrador", 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Seguimiento $seguimiento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Seguimiento $seguimiento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SeguimientoRequest $request, Seguimiento $Seguimiento)
    {
        try {
            DB::beginTransaction();
            // dd($Seguimiento, $request->validated());
            $Seguimiento->update($request->validated());
            DB::commit();
            return response()->json($Seguimiento, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            // return response()->json($e->getMessage(), 500);
            return response()->json('Error, consulte al administrador', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Seguimiento $seguimiento)
    {
        //
    }

    public function seguimientoProfesor(Request $request)
    {
        $search = $request->query('search');
        $query = [];
        if ($search) {
            // usa la relación de Spatie
            $query = User::role('profesor')
                ->where('name', 'like', '%' . $search . '%')
                ->orderBy('id', 'desc')
                ->limit(5)
                ->get(['id', 'name']);
        }
        return response()->json(
            $query,
            200
        );
    }

    public function seguimientoMateria(Request $request)
    {
        $search = $request->query('search');
        $query = [];
        if ($search) {
            //Consulta
            $query = Materia::where('nombre', 'like', '%' . $search . '%')
                ->orderBy('id', 'desc')
                ->limit(5)
                ->get(['id', 'nombre']);
        }
        return response()->json(
            $query,
            200
        );
    }
    // seguimientoSemestre
    public function seguimientoSemestre(Request $request)
    {
        $search = $request->query('search');
        $query = [];
        if ($search) {
            //Consulta
            $query = Semestre::where('nombre', 'like', '%' . $search . '%')
                ->orderBy('id', 'desc')
                ->limit(5)
                ->get(['id', 'nombre']);
        }
        return response()->json(
            $query,
            200
        );
    }
    // seguimientoGrupo
    public function seguimientoGrupo(Request $request)
    {
        $search = $request->query('search');
        $query = [];
        if ($search) {
            // usa la Join
            $query = Grupo::join('grados', 'grupos.grado_id', '=', 'grados.id')
                ->where(function ($q) use ($search) {
                    $q->where('grados.nombre', 'like', '%' . $search . '%')
                        ->orWhere('grupos.nombre', 'like', '%' . $search . '%');
                })
                ->orderBy('grupos.id', 'desc')
                ->limit(5)
                ->select([
                    'grupos.id',
                    DB::raw("CONCAT(grados.nombre, ' - ', grupos.nombre) as nombre")
                ])
                ->get();
        }
        return response()->json(
            $query,
            200
        );
    }
    // seguimientoCarrera
    public function seguimientoCarrera(Request $request)
    {
        $search = $request->query('search');
        $query = [];
        if ($search) {
            //Consulta
            $query = Carrera::where('nombre', 'like', '%' . $search . '%')
                ->orderBy('id', 'desc')
                ->limit(5)
                ->get(['id', 'nombre']);
        }
        return response()->json(
            $query,
            200
        );
    }

    // formato1
    public function formato1(Seguimiento $Seguimiento)
    {
        // Paso 1: Obtener parciales dinámicos
        $parciales = DB::table('parciales')->pluck('id', 'nombre');

        // Paso 2: Armar el array de selects básicos
        $selects = [
            'e.listaNumero as No_DE_LISTA',
            DB::raw("'M' as SEXO"),  // Puedes cambiar si tienes el campo sexo
            'u.name as NOMBRE_DEL_ALUMNO',
            DB::raw('COALESCE(SUM(CASE WHEN a.status = "ausente" THEN 1 ELSE 0 END), 0) as FALTAS_DE_ASISTENCIA'),
            DB::raw('COALESCE(SUM(CASE WHEN a.status = "ausente" THEN 1 ELSE 0 END), 0) as TOT_DE_FALTAS'),
            DB::raw('ROUND(COALESCE(SUM(CASE WHEN a.status = "ausente" THEN 1 ELSE 0 END), 0) / NULLIF(COUNT(a.id), 0) * 100, 1) as PORC_INASISTENCIA'),
        ];

        // Paso 3: Agregar dinámicamente calificaciones y sumas por parcial
        foreach ($parciales as $nombre => $id) {
            $aliasEval = preg_replace('/[^A-Za-z0-9]/', '_', $nombre);  // Limpia el alias
            $aliasSuma = $aliasEval;

            $selects[] = DB::raw("MAX(CASE WHEN ev.parcial_id = $id THEN ev.calificacion_parcial END) as eval_$aliasEval");
            $selects[] = DB::raw("MAX(CASE WHEN ev.parcial_id = $id THEN ev.suma END) as suma_$aliasSuma");
        }

        // Paso 4: Agregar promedio y observaciones
        $selects[] = DB::raw('ROUND(AVG(ev.calificacion_parcial), 1) as PROMEDIO');
        $selects[] = DB::raw("CASE WHEN ROUND(AVG(ev.calificacion_parcial), 1) < 6 THEN 'E. EXTR.' ELSE '' END as OBSERVACIONES");

        // Paso 5: Ejecutar consulta
        $resultados = DB::table('listas')
            ->where('listas.seguimiento_id', $Seguimiento->id)
            ->join('users as u', 'listas.alumno_id', '=', 'u.id')
            ->join('estudiantes as e', 'u.id', '=', 'e.user_id')
            ->leftJoin('evaluaciones as ev', 'listas.id', '=', 'ev.lista_id')
            ->leftJoin('asistencias as a', 'e.id', '=', 'a.estudiante_id')
            ->select($selects)
            ->groupBy('e.id', 'e.listaNumero', 'u.name')
            ->orderBy('e.listaNumero')
            ->get();
        // dd($Seguimiento);
        // $resultados = Lista::where('seguimiento_id', $Seguimiento->id)
        //     ->join('users as u', 'listas.alumno_id', '=', 'u.id')
        //     ->join('estudiantes as e', 'u.id', '=', 'e.user_id')
        //     ->leftJoin('evaluaciones as ev', 'listas.id', '=', 'ev.lista_id')
        //     ->leftJoin('asistencias as a', 'e.id', '=', 'a.estudiante_id')
        //     ->select(
        //         'e.listaNumero as No_DE_LISTA',
        //         DB::raw("'M' as SEXO"),  // Ajusta según corresponda
        //         'u.name as NOMBRE_DEL_ALUMNO',
        //         DB::raw('COALESCE(SUM(CASE WHEN a.status = "ausente" THEN 1 ELSE 0 END), 0) as FALTAS_DE_ASISTENCIA'),
        //         DB::raw('COALESCE(SUM(CASE WHEN a.status = "ausente" THEN 1 ELSE 0 END), 0) as TOT_DE_FALTAS'),
        //         DB::raw('ROUND(COALESCE(SUM(CASE WHEN a.status = "ausente" THEN 1 ELSE 0 END), 0) / NULLIF(COUNT(a.id), 0) * 100, 1) as PORC_INASISTENCIA'),
        //         DB::raw('MAX(CASE WHEN ev.parcial_id = 1 THEN ev.calificacion_parcial END) as 1a'),
        //         DB::raw('MAX(CASE WHEN ev.parcial_id = 2 THEN ev.calificacion_parcial END) as 2a'),
        //         DB::raw('MAX(CASE WHEN ev.parcial_id = 3 THEN ev.calificacion_parcial END) as 3a'),
        //         DB::raw('MAX(CASE WHEN ev.parcial_id = 1 THEN ev.suma END) as SUMA_1a'),
        //         DB::raw('MAX(CASE WHEN ev.parcial_id = 2 THEN ev.suma END) as SUMA_2a'),
        //         DB::raw('MAX(CASE WHEN ev.parcial_id = 3 THEN ev.suma END) as SUMA_3a'),
        //         DB::raw('ROUND(AVG(ev.calificacion_parcial), 1) as PROMEDIO'),
        //         DB::raw("CASE WHEN ROUND(AVG(ev.calificacion_parcial), 1) < 6 THEN 'E. EXTR.' ELSE '' END as OBSERVACIONES")
        //     )
        //     ->groupBy('e.id', 'e.listaNumero', 'u.name')
        //     ->orderBy('e.listaNumero')
        //     ->get();
        // dd($resultados->toarray());
        return response()->json($resultados->toarray(), 200);
    }
}
