<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\SeguimientoRequest;
use App\Models\{Carrera, Grupo, Materia, User, Seguimiento, Semestre,};

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
            return response()->json('Exito',201);
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
}
