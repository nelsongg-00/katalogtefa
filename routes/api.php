<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProdukController;

Route::get('/produk', [ProdukController::class, 'index']);
Route::post('/login', [AuthController::class, 'login']);
