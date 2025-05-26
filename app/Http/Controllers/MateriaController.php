<?php

namespace App\Http\Controllers;

use App\Http\Requests\MateriaRequest;
use App\Models\Materia;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Return_;

class MateriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $serach = $request->query('search');
        $query = Materia::query();
        if ($serach) {
            $query->where('nombre', 'like', '%' . $serach . '%')
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
    public function store(MateriaRequest $request)
    {
        try {
            DB::beginTransaction();
            $Materia = Materia::create($request->validated());
            DB::commit();
            return response()->json($Materia,201);
        } catch (\Exception $e) {
            DB::rollBack();
            // return response()->json($e->getMessage(), 500);
            return response()->json('Error, consulte al administrador', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Materia $materia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Materia $materia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MateriaRequest $request, Materia $Materia)
    {
        try {
            DB::beginTransaction();
            $Materia->update($request->validated());
            DB::commit();
            return response()->json($Materia,200);
        } catch (\Exception $e) {
            DB::rollBack();
            // return response()->json($e->getMessage(), 500);
            return response()->json('Error, consulte al administrador', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Materia $materia)
    {
        //
    }
}
