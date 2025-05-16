<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\BienvenidaCrearPassword;
use App\Http\Requests\UsuarioRequest;
use Illuminate\Support\Facades\Password;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = User::query();

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->limit(5);
        }
        return response()->json($query->orderBy('id', 'desc')->get(['id', 'name', 'email', 'estatus']), 200);
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

            $user = User::create($data);

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
            DB::commit();
            return response()->json(['message' => 'Usuario creado correctamente'], 201);
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
        try {
            DB::beginTransaction();
            $nuevoEstatus = $User->estatus === 'habilitado' ? 'deshabilitado' : 'habilitado';
            $User->update([ 'estatus' => $nuevoEstatus ]);
            DB::commit();
            return response()->json(['message' => 'Estatus modificado correctamente'], 201);
        } catch (\Exception $e) {
            //Si hay un error / excepción en el código anterior antes de confirmar, se revertirá
            DB::rollBack();
            // return response()->json($e->getMessage(), 500);
            return response()->json(['error' => 'No se actualizó el registro, consulte al administrador', 'detalle' => $e->getMessage()], 500);
        }
    }
}
