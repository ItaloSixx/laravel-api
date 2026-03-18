<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImportacaoController;
use App\Http\Controllers\QuartoController;
use App\Http\Controllers\ReservaController;

Route::prefix('v1')->group(function () {
    Route::post('/import/hotels', [ImportacaoController::class, 'importarHoteis']);
    Route::post('/import/rooms', [ImportacaoController::class, 'importarQuartos']);
    Route::post('/import/rates', [ImportacaoController::class, 'importarTarifas']);
    Route::post('/import/reservations', [ImportacaoController::class, 'importarReservas']);

    Route::apiResource('rooms', QuartoController::class);
    Route::get('/reservations', [ReservaController::class, 'index']);
    Route::post('/reservations', [ReservaController::class, 'store']);
});
