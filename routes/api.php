<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\Api\ProdukController;
use App\Http\Controllers\Api\AuthController;

Route::get('/produk', [ProdukController::class, 'index']);
Route::post('/login', [AuthController::class, 'login']);