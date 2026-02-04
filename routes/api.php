<?php

use App\Http\Controllers\ClientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/clients', [ClientController::class, 'index']);
Route::post('/clients/lookup', [ClientController::class, 'lookup']);
Route::get('/clients/{client}/websites', [ClientController::class, 'websites']);

use App\Http\Controllers\WebsiteController;
Route::post('/websites', [WebsiteController::class, 'store']);
Route::get('/websites/{website}/stats', [WebsiteController::class, 'stats']);
Route::delete('/websites/{website}', [WebsiteController::class, 'destroy']);
