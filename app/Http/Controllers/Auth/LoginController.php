<?php

namespace App\Http\Controllers\Auth;

use App\Models\{Image};
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class LoginController extends Controller
{
    public function __construct()
    {
        // Proteger solo estas funciones con auth:api (JWT)
        $this->middleware('auth:api')->only(['user', 'logout', 'refresh']);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }

        return response()->json(['token' => $token]);
    }
    public function user(Request $request)
    {
        $user = $request->user()->load(['image', 'roles.permissions']);
        $role = $user->roles->first(); // Solo uno
        return response()->json([
            'user' => $user,
            'avatar_url' => $user->image->url ?? asset('storage/avatars/Def/avatar.jpg'),
            'role' => $role ? [
                'name' => $role->name,
                'permissions' => $role->permissions->pluck('name'),
            ] : null,
        ]);
        // return response()->json($request->user());
    }
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Sesión cerrada']);
    }
    public function Imagen(Request $request)
    {
        $user = auth()->user();

        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');

            // Guarda la imagen en /storage/app/public/avatars
            $path = $imageFile->store('avatars', 'public');

            // Elimina imagen anterior si existe
            if ($user->image) {
                Storage::disk('public')->delete($user->image->path);
                $user->image()->delete();
            }

            // Crea el nuevo registro en la tabla images
            $image = new Image(['path' => $path]);
            $user->image()->save($image);

            return response()->json([
                'message' => 'Imagen guardada correctamente',
                'path' => $path,
                'url' => $image->url, // accede al accessor
            ]);
        }

        return response()->json(['message' => 'No se recibió ninguna imagen'], 422);
    }

    public function refresh(Request $request)
    {
        $token = JWTAuth::getToken();

        if (!$token) {
            return response()->json(['error' => 'Token no proporcionado'], 401);
        }

        try {
            $newToken = JWTAuth::refresh($token);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['error' => 'Token inválido'], 401);
        }

        return response()->json([
            'token' => $newToken,
        ]);
    }

    public function Perfil(UpdateUserRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = auth()->user();
            // Validar que la contraseña actual coincida (si se envía)
            if (!\Hash::check($request->password_hold, $user->password)) {
                return response()->json([
                    'errors' => ['password_hold' => ['La contraseña actual no es correcta.']]
                ], 422);
            }

            // Actualiza campos
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'telefono' => $request->telefono,
                'domicilio' => $request->domicilio,
                'localidadColonia' => $request->localidadColonia,
                // solo actualiza si se envió una nueva contraseña
                'password' => $request->filled('password') ? bcrypt($request->password) : $user->password,
            ]);

            DB::commit();
            return response()->json(['message' => 'Perfil actualizado correctamente.'], 200);
        } catch (\Exception $e) {
            // return response()->json($e->getMessage(), 500);
            return response()->json('Error, consulte al administrador.', 500);
        }
    }
}
