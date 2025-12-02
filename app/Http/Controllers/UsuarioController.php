<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Escuela;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\EscuelaRequest;
use App\Http\Requests\UsuarioRequest;
use App\Mail\BienvenidaCrearPassword;
use Illuminate\Support\Facades\Password;
use Illuminate\Container\Attributes\Auth;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $query = User::query();

        // Mapa Permiso → Nombre del Rol
        $restricciones = [
            'Puede ver estudiantes' => 'estudiante',
            'Puede ver profesores' => 'profesor',
            'Puede ver orientadores' => 'orientador',
            'Puede ver administradores' => 'admin',
        ];
        $user = auth('api')->user();
        // Aplicar restricciones basado en permisos
        foreach ($restricciones as $permiso => $rol) {
            if (!$user->can($permiso)) {
                // si NO tiene permiso, excluir usuarios con ese rol
                $query->whereDoesntHave('roles', function ($q) use ($rol) {
                    $q->where('name', $rol);
                });
            }
        }

        // 🔵 Búsqueda
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('sexo', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('estatus', 'like', '%' . $search . '%')
                    ->orWhere('telefono', 'like', '%' . $search . '%')
                    ->orWhere('domicilio', 'like', '%' . $search . '%')
                    ->orWhere('localidadColonia', 'like', '%' . $search . '%')
                    ->orWhereHas('roles', function ($r) use ($search) {
                        $r->where('name', 'like', '%' . $search . '%');
                    })
                    ->limit(5);
            });
        }
        // if ($search) {
        //     $query->where('name', 'like', '%' . $search . '%')
        //         ->orWhere('email', 'like', '%' . $search . '%')
        //         ->orWhere('estatus', 'like', '%' . $search . '%')
        //         ->orWhere('telefono', 'like', '%' . $search . '%')
        //         ->orWhere('domicilio', 'like', '%' . $search . '%')
        //         ->orWhere('localidadColonia', 'like', '%' . $search . '%')
        //         ->orWhereHas('roles', function ($q) use ($search) {
        //             $q->where('name', 'like', '%' . $search . '%');
        //         })
        //         ->limit(5);
        // }
        return response()->json($query->orderBy('id', 'desc')->get([
            'id',
            'name',
            'sexo',
            'email',
            'estatus',
            'telefono',
            'domicilio',
            'localidadColonia'
        ]), 200);
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
    public function store(UsuarioRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            // Encriptar contraseña si viene
            if (!empty($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            }
            // Crear usuario
            $user = User::create($data);

            // Asignar rol
            $user->roles()->sync([$data['RolId']]);

            $token = Password::createToken($user);
            $mensaje = [
                'Has sido registrado exitosamente en nuestro sistema.',
                'Para poder acceder, primero necesitas configurar tu contraseña.'
            ];
            Mail::to($user->email)->send(
                new BienvenidaCrearPassword(
                    $user,
                    $token,
                    'Crear contraseña',
                    $mensaje
                )
            );

            DB::commit();
            return response()->json(['message' => 'Usuario creado correctamente'], 201);
        } catch (\Exception $e) {
            //Si hay un error / excepción en el código anterior antes de confirmar, se revertirá
            DB::rollBack();
            return response()->json($e->getMessage(), 500);
            return response()->json("No se creo el registro, consulte al administrador", 500);
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
    public function update(UsuarioRequest $request, Int $user)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            // Encriptar contraseña si viene
            if (!empty($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            }
            $User = User::findOrFail($user);
            $User->update($data);
            // Reemplazar todos los roles con el nuevo rol enviado
            $User->roles()->sync([$data['RolId']]);
            DB::commit();
            return response()->json(['message' => 'Usuario actualizado correctamente.'], 201);
        } catch (\Exception $e) {
            //Si hay un error / excepción en el código anterior antes de confirmar, se revertirá
            DB::rollBack();
            // return response()->json($e->getMessage(), 500);
            return response()->json("No se modifico el registro, consulte al administrador", 500);
        }
    }

    /**
     * Actualizar Estatus del usuario
     */
    public function Estatus(User $User)
    {
        $this->authorize('cambiarEstatus', $User);
        try {
            DB::beginTransaction();
            $nuevoEstatus = $User->estatus === 'habilitado' ? 'deshabilitado' : 'habilitado';
            $User->update(['estatus' => $nuevoEstatus]);
            DB::commit();
            return response()->json(['message' => 'Estatus modificado correctamente'], 201);
        } catch (\Exception $e) {
            //Si hay un error / excepción en el código anterior antes de confirmar, se revertirá
            DB::rollBack();
            // return response()->json($e->getMessage(), 500);
            return response()->json(['error' => 'No se actualizó el registro, consulte al administrador', 'detalle' => $e->getMessage()], 500);
        }
    }

    public function escuela()
    {
        $Escuela = Escuela::first();
        return response()->json([
            'nombre_escuela' => $Escuela?->nombre_escuela ?? null,
            'direccion' => $Escuela?->direccion ?? null,
            'departamento' => $Escuela?->departamento ?? null,
            'turno' => $Escuela?->turno ?? null,
            'nivel' => $Escuela?->nivel ?? null,
            'clave_trabajo' => $Escuela?->clave_trabajo ?? null,
            'numero_cct' => $Escuela?->numero_cct ?? null,
            'zona_escolar' => $Escuela?->zona_escolar ?? null,
            'domicilio' => $Escuela?->domicilio ?? null,
            'localidad_colonia' => $Escuela?->localidad_colonia ?? null,
            'municipio' => $Escuela?->municipio ?? null,
            'telefono' => $Escuela?->telefono ?? null,
            'docente' => $Escuela?->docente ?? null,
            'subdirector_escolar' => $Escuela?->subdirector_escolar ?? null,
            'director_escolar' => $Escuela?->director_escolar ?? null,
            'secretario_escolar' => $Escuela?->secretario_escolar ?? null,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateEscuela(EscuelaRequest $request)
    {
        try {
            DB::beginTransaction();
            // $request ya contiene datos validados
            $Escuela = Escuela::updateOrCreate(['id' => 1], $request->validated());
            DB::commit();
            return response()->json([
                'message' => 'Escuela guardada correctamente.',
                'Escuela' => $Escuela
            ], 201);
        } catch (\Exception $e) {
            //Si hay un error / excepción en el código anterior antes de confirmar, se revertirá
            DB::rollBack();
            // return response()->json($e->getMessage(), 500);
            return response()->json("No se modifico el registro, consulte al administrador", 500);
        }
    }
}
