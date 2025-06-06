<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{RegistroHorasDocencia, Carrera};
use App\Http\Requests\RegistroHoraDocenciaRequest;

class RegistroHorasDocenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = RegistroHorasDocencia::with(['carrera:id,nombre as text']);

        if ($search) {
            $query->where('mes', 'like', '%' . $search . '%')
                ->OrWhere('horasImpartidas', 'like', '%' . $search . '%')
                ->orWhereHas('carrera', function ($q) use ($search) {
                    $q->where('nombre', 'like', '%' . $search . '%');
                })
                ->limit(5);
        }

        return response()->json($query->orderBy('id', 'desc')->get(['id', 'mes', 'horasImpartidas', 'carrera_id']), 200);
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
    public function store(RegistroHoraDocenciaRequest $request)
    {
        try {
            DB::beginTransaction();
            $RegistroHorasDocencia = RegistroHorasDocencia::create($request->validated());
            DB::commit();
            return response()->json($RegistroHorasDocencia, 201);
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
    public function show(String $Mes, Request $request)
    {
        $search = $request->query('search');
        // Buscar carreras
        $query = Carrera::query();
        $data = [];
        if ($search) {
            $data = $query->where('nombre', 'like', '%' . $search . '%')
                ->orderBy('nombre')
                ->limit(10)
                ->get(['id', 'nombre']);
        }
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RegistroHorasDocencia $registroHorasDocencia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RegistroHoraDocenciaRequest $request, RegistroHorasDocencia $RegistroHorasDocencia)
    {
        try {
            DB::beginTransaction();
            $RegistroHorasDocencia->update($request->validated());
            DB::commit();
            return response()->json($RegistroHorasDocencia, 200);
        } catch (\Exception $e) {
            //Si hay un error / excepción en el código anterior antes de confirmar, se revertirá
            DB::rollBack();
            // return response()->json($e->getMessage(),500);
            return response()->json("No se creo el registro, consulte al administrador", 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RegistroHorasDocencia $registroHorasDocencia)
    {
        //
    }
}
