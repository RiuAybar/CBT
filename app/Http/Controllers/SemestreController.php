<?php

namespace App\Http\Controllers;

use App\Http\Requests\SemestreRequest;
use App\Models\Semestre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SemestreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Semestre::query();

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
    public function store(SemestreRequest $request)
    {
        try {
            DB::beginTransaction();
            $Semestre = Semestre::create($request->validated());
            DB::commit();
            return response()->json($Semestre, 201);
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
    public function show(Semestre $semestre)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Semestre $semestre)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SemestreRequest $request, Semestre $Semestre)
    {
        try {
            DB::beginTransaction();
            $Semestre->update($request->validated());
            DB::commit();
            return response()->json($Semestre, 200);
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
    public function destroy(Semestre $semestre)
    {
        //
    }
}
