<?php

namespace App\Http\Controllers;

use App\Models\Seguimiento;
use Illuminate\Http\Request;

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
            ->join('grupos', 'seguimientos.semestre_id', '=', 'grupos.id')
            ->join('carreras', 'seguimientos.carrera_id', '=', 'carreras.id')
            ->join('users as profesores', 'seguimientos.profesor_id', '=', 'profesores.id')
            ->orderBy('seguimientos.id', 'desc')
            ->select([
                'seguimientos.id',
                'seguimientos.ano',
                'materias.nombre as materias',
                'semestres.nombre as semestres',
                'grupos.nombre as grupos',
                'carreras.nombre as carreras',
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
                    ->orWhere('carreras.nombre', 'like', "%$search%")
                    ->orWhere('profesores.name', 'like', "%$search%");
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
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, Seguimiento $seguimiento)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Seguimiento $seguimiento)
    {
        //
    }
}
