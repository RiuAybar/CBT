<?php

namespace App\Http\Controllers;

use App\Models\Parcial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ParcialRequest;

class ParcialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Parcial::query();

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
    public function store(ParcialRequest $request)
    {
        try {
            DB::beginTransaction();
            $Parcial = Parcial::create($request->validated());
            DB::commit();
            return response()->json($Parcial, 201);
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
    public function show(Parcial $parcial)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Parcial $parcial)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ParcialRequest $request, Parcial $Parcial)
    {
        try {
            DB::beginTransaction();
            $Parcial->update($request->validated());
            DB::commit();
            return response()->json($Parcial, 200);
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
    public function destroy(Parcial $parcial)
    {
        //
    }
}
