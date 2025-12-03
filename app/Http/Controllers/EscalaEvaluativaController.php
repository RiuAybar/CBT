<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EscalaEvaluativa;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\EscalaEvaluativaRequest;

class EscalaEvaluativaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', EscalaEvaluativa::class);
        $search = $request->query('search');
        $query = EscalaEvaluativa::query();
        if ($search) {
            $query->where('nombre', 'like', '%' . $search . '%')
                ->orWhere('abreviatura', 'like', '%' . $search . '%')
                ->limit(5);
        }
        return response()->json($query->orderBy('id', 'desc')->get(['id', 'nombre','abreviatura']), 200);
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
    public function store(EscalaEvaluativaRequest $request)
    {
        $this->authorize('create', EscalaEvaluativa::class);
        try {
            DB::beginTransaction();
            $Grado = EscalaEvaluativa::create($request->validated());
            DB::commit();
            return response()->json($Grado, 201);
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
    public function show(EscalaEvaluativa $escalaEvaluativa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EscalaEvaluativa $escalaEvaluativa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EscalaEvaluativaRequest $request, EscalaEvaluativa $EscalaEvaluativa)
    {
        $this->authorize('update', $EscalaEvaluativa);
        try {
            DB::beginTransaction();
            $EscalaEvaluativa->update($request->validated());
            DB::commit();
            return response()->json($EscalaEvaluativa, 200);
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
    public function destroy(EscalaEvaluativa $escalaEvaluativa)
    {
        //
    }
}
