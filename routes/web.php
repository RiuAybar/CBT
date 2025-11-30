<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SeguimientoController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', function () {
//     return view('app');
// });

Route::get('reporte/{Seguimiento}',[SeguimientoController::class,'reporte']);

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
