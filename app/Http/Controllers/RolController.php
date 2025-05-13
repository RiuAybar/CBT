<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RolRequest;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Role::query();

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->limit(5);
        }
        return response()->json($query->orderBy('id', 'desc')->get(['id', 'name']), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RolRequest $request)
    {
        try {
            DB::beginTransaction();
            $permission = Role::create($request->validated());
            DB::commit();
            return response()->json($permission, 201);
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
    public function show(Role $Role)
    {
        return response()->json($Role->only(['id', 'name']), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RolRequest $request, Role $Role)
    {
        try {
            DB::beginTransaction();
            $Role->update($request->validated());
            DB::commit();
            return response()->json($Role, 200);
        } catch (\Exception $e) {
            //Si hay un error / excepción en el código anterior antes de confirmar, se revertirá
            DB::rollBack();
            // return response()->json($e->getMessage(),500);
            return response()->json("No se creo el registro, consulte al administrador", 500);
        }
    }

    public function Permisos(Request $request, Role $Role)
    {
        $search = $request->query('search');

        $query = $Role->permissions(); // usa la relación de Spatie

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->limit(5);
        }

        return response()->json(
            $query->orderBy('id', 'desc')->get(['id', 'name']),
            200
        );
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Role $Role)
    {
        try {
            DB::beginTransaction();
            // Elimina el permiso del rol sin borrar el permiso como tal
            $Role->revokePermissionTo($request->Permiso);
            DB::commit();
            return response()->json($Role, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            // return response()->json($e->getMessage(),500);
            return response()->json("No se elimino el producto, consulte al administrador", 500);
        }
    }
    public function permisosDisponibles(Request $request, Role $Role)
    {
        $search = $request->query('search');
        $data = [];
        // if($search){
            // Obtener IDs de permisos ya asignados
            $permisosAsignados = $Role->permissions()->pluck('id')->toArray();
    
            // Evitar permisos ya asignados
            $query = Permission::whereNotIn('id', $permisosAsignados);
    
            if ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->limit(5);
            }
            $data = $query->orderBy('id', 'desc')->get(['id', 'name']);
        // }

        return response()->json(
            $data,
            200
        );
    }

    // POST /api/roles/{role}/asignar-permiso
    public function asignarPermiso(Request $request, Role $role)
    {
        $request->validate([
            'permiso_id' => 'required|exists:permissions,id'
        ]);

        $role->givePermissionTo($request->permiso_id);

        return response()->json(['message' => 'Permiso asignado correctamente'], 200);
    }
}
