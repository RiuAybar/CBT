<?php

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');

    if (!$token = JWTAuth::attempt($credentials)) {
        return response()->json(['error' => 'Credenciales inválidas'], 401);
    }

    return response()->json(['token' => $token]);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return response()->json($request->user());
});

Route::middleware('auth:api')->post('/logout', function () {
    JWTAuth::invalidate(JWTAuth::getToken());
    return response()->json(['message' => 'Sesión cerrada']);
});

// routes/api.php
Route::get('/users', function (Request $request) {
    $search = $request->query('search');
    
    $query = \App\Models\User::query();

    if ($search) {
        $query->where('name', 'like', "%$search%")
              ->orWhere('email', 'like', "%$search%");
    }

    return $query->get();
});


Route::middleware('auth:api')->prefix('/')->group(function () {
    Route::resource('/productos', ProductoController::class)->except(['create', 'show','edit']);
    Route::resource('/preguntas', PreguntaController::class)->except(['create', 'show','edit']);
    Route::resource('/usuarios', UserController::class)->except(['create', 'show','edit'])->parameters([
        'usuarios'
        => 
        'user' 
    ]);
    // más rutas aquí
});
