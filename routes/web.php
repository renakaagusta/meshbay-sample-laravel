<?php

use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn (): array => [
    'name' => 'meshbay-sample-laravel',
    'version' => 'v1',
    'endpoints' => ['GET /health', 'GET /api/items', 'POST /api/items', 'GET /api/items/{id}', 'PUT /api/items/{id}', 'DELETE /api/items/{id}'],
]);

Route::get('/health', [ItemController::class, 'health']);
Route::get('/api/items', [ItemController::class, 'index']);
Route::post('/api/items', [ItemController::class, 'store']);
Route::get('/api/items/{itemID}', [ItemController::class, 'show'])->whereNumber('itemID');
Route::put('/api/items/{itemID}', [ItemController::class, 'update'])->whereNumber('itemID');
Route::delete('/api/items/{itemID}', [ItemController::class, 'destroy'])->whereNumber('itemID');
