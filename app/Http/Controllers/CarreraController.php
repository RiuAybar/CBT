<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CarreraRequest;

class CarreraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Carrera::query();

        if ($search) {
            $query->where('nombre', 'like', '%' . $search . '%')
                ->limit(5);
        }
        return response()->json($query->orderBy('id', 'desc')->get(['id', 'nombre']), 200);
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
    public function store(CarreraRequest $request)
    {
        try {
            DB::beginTransaction();
            $Carrera = Carrera::create($request->validated());
            DB::commit();
            return response()->json($Carrera, 201);
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
    public function show(Carrera $carrera)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Carrera $carrera)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CarreraRequest $request, Carrera $Carrera)
    {
        try {
            DB::beginTransaction();
            $Carrera->update($request->validated());
            DB::commit();
            return response()->json($Carrera, 200);
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
    public function destroy(Carrera $carrera)
    {
        //
    }
}
