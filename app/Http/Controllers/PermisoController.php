<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
            $query->where('name', 'like', '%' . $search . '%')
            ->limit(5);
        }
        return response()->json($query->orderBy('id', 'desc')->get(['id','name']), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PermisoRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $data['guard_name'] = 'api';
            $permission = Permission::create($data);
            DB::commit();
            return response()->json($permission, 201);
        } catch (\Exception $e) {
            //Si hay un error / excepción en el código anterior antes de confirmar, se revertirá
            DB::rollBack();
            // return response()->json($e->getMessage(),500);
            return response()->json("No se creo el registro, consulte al administrador",500);

        }
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
            return response()->json("No se creo el registro, consulte al administrador",500);

        }
    }
}
