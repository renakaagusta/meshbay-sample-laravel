<?php

use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn (): array => [
    'name' => 'meshbay-sample-laravel',
    'version' => 'v1',
    'endpoints' => ['GET /health', 'GET /api/items', 'POST /api/items'],
]);

Route::get('/health', [ItemController::class, 'health']);
Route::get('/api/items', [ItemController::class, 'index']);
Route::post('/api/items', [ItemController::class, 'store']);
