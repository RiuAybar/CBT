<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SeguimientoHorario;
use App\Http\Requests\SeguimientoHorariosRequest;
use PhpParser\Node\Stmt\TryCatch;

class SeguimientoHorariosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, int $seguimiento_id)
    {
        $search = $request->query('search');

        $query = SeguimientoHorario::where('seguimiento_id', $seguimiento_id);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('dia', 'like', '%' . $search . '%')
                    ->orWhere('hora_inicio', 'like', '%' . $search . '%')
                    ->orWhere('hora_fin', 'like', '%' . $search . '%');
            });
        }

        // Orden personalizado de días
        $diasOrdenados = "'LUNES','MARTES','MIERCOLES','JUEVES','VIERNES','SABADO','DOMINGO'";

        $query->orderByRaw("FIELD(dia, $diasOrdenados)")
            ->orderBy('hora_inicio');

        return response()->json(
            $query->get(['id', 'dia', 'hora_inicio', 'hora_fin']),
            200
        );
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
    public function store(SeguimientoHorariosRequest $request)
    {
        // $this->authorize('create', Grado::class);
        try {
            DB::beginTransaction();
            $SeguimientoHorario = SeguimientoHorario::create($request->validated());
            DB::commit();
            return response()->json($SeguimientoHorario, 201);
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
    public function show(SeguimientoHorario $seguimientoHorarios)
    {
        return response()->json($seguimientoHorarios->only(['id']), 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SeguimientoHorario $seguimientoHorarios)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SeguimientoHorariosRequest $request, SeguimientoHorario $SeguimientoHorario)
    {
        //  $this->authorize('update', $Materia);
        try {
            DB::beginTransaction();
            $SeguimientoHorario->update($request->validated());
            DB::commit();
            return response()->json($SeguimientoHorario, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            // return response()->json($e->getMessage(), 500);
            return response()->json('Error, consulte al administrador', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SeguimientoHorario $SeguimientoHorario)
    {
        try {
            DB::beginTransaction();
            // Elimina el permiso del rol sin borrar el permiso como tal
            $SeguimientoHorario->delete();
            DB::commit();
            return response()->json($SeguimientoHorario, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            // return response()->json($e->getMessage(),500);
            return response()->json("No se elimino el producto, consulte al administrador", 500);
        }
    }
}
