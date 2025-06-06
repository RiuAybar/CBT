<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\RestablecerContraseñaController;
use App\Http\Controllers\CarreraController;
use App\Http\Controllers\GradoController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\ListaController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\RegistroHorasDocenciaController;
use App\Http\Controllers\SemestreController;

Route::get('/user', [LoginController::class, 'user']);
Route::post('/refresh', [LoginController::class, 'refresh']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);
Route::post('/password/reset', [RestablecerContraseñaController::class, 'restablecer']);
Route::post('/sendResetLink', [ForgotPasswordController::class, 'sendResetLink']);

Route::middleware('auth:api')->prefix('/')->group(function () {

    // Rutas permisos Usuarios
    Route::resource('Permission', PermisoController::class)->only(['index', 'store', 'update']);
    Route::resource('Role', RolController::class)->only(['index', 'store', 'show', 'update']);
    Route::get('Role/{Role}/Permiso', [RolController::class, 'Permisos']);
    Route::delete('Role/{Role}/destroy', [RolController::class, 'destroy']);
    Route::get('/roles/{role}/permisosDisponibles', [RolController::class, 'permisosDisponibles']);
    Route::post('/roles/{role}/asignarPermiso', [RolController::class, 'asignarPermiso']);
    
    Route::prefix('/gestion')->group(function () {
        Route::resource('user', UsuarioController::class);
        Route::put('user/{User}/Estatus', [UsuarioController::class, 'Estatus']);
        Route::get('/roles', [RolController::class, 'buscarRoles']);
    });
    Route::prefix('/Estudiuante')->group(function () {
        Route::resource('Lista', ListaController::class);
    });

    Route::prefix('/Logistica')->group(function () {
        Route::resource('Grado', GradoController::class)->only(['index', 'store', 'update']);
        Route::resource('Grupo', GrupoController::class)->only(['index', 'store', 'update']);
        Route::resource('Semestre', SemestreController::class)->only(['index', 'store', 'update']);
        Route::resource('Carrera', CarreraController::class)->only(['index', 'store', 'update']);
        Route::resource('RegistroHorasDocencia', RegistroHorasDocenciaController::class)->only(['index', 'store', 'update','show'])
            ->parameters(['RegistroHorasDocencia'=>'RegistroHorasDocencia']);;
        Route::resource('Materia', MateriaController::class)->only(['index', 'store', 'update'])->parameters(['Materia'=>'Materia']);
    });
    Route::prefix('/Actualizar')->group(function () {
        Route::post('/User', [LoginController::class, 'Perfil']);
        Route::post('/UserImagen', [LoginController::class, 'Imagen']);
    }); 
    // Route::resource('/usuarios', UserController::class)->except(['create', 'show','edit'])->parameters([
    //     'usuarios'
    //     => 
    //     'user' 
    // ]);
    // más rutas aquí
});
