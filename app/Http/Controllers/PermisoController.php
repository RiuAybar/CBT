<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PermisoRequest;
use Spatie\Permission\Models\Permission;

class PermisoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Permission::query();

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }
        return response()->json($query->orderBy('id', 'desc')->get(['id','name']), 200);
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
    public function store(PermisoRequest $request)
    {
        try {
            DB::beginTransaction();
            $permission = Permission::create($request->validated());
            DB::commit();
            return response()->json($permission, 201);
        } catch (\Exception $e) {
            //Si hay un error / excepción en el código anterior antes de confirmar, se revertirá
            DB::rollBack();
            // return response()->json($e->getMessage(),500);
            return response()->json("No se creo el producto, consulte al administrador",500);

        }
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
    public function update(PermisoRequest $request, Permission $Permission)
    {
        try {
            DB::beginTransaction();
            $Permission->update($request->validated());
            DB::commit();
            return response()->json($Permission, 200);
        } catch (\Exception $e) {
            //Si hay un error / excepción en el código anterior antes de confirmar, se revertirá
            DB::rollBack();
            // return response()->json($e->getMessage(),500);
            return response()->json("No se creo el producto, consulte al administrador",500);

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
