<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Estudiante;
use App\Models\Asistencia;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class ListaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function destroy(string $id)
    {
        //
    }
}
