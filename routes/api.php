<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Teknik\TeknikTicketController;
use App\Http\Controllers\Api\Teknik\OptionController;
use App\Http\Controllers\Api\Teknik\DashboardController;
use App\Http\Controllers\Api\Ai\ChattController;
use App\Http\Controllers\Api\Ai\ExportController;
use App\Http\Controllers\Api\Ai\AiAnalysisController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\Agent\TiketController; 
use App\Http\Controllers\Api\Agent\DashboardAdminController; 

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum'])->group(function () {
    // User Management
    Route::apiResource('users', UserController::class);
    Route::get('/roles', [UserController::class, 'getRoles']);

    Route::get('/teknik-get', [TeknikTicketController::class, 'index']);
    Route::get('/teknik-get/{id}', [TeknikTicketController::class, 'show']);
    Route::post('/teknik-get', [TeknikTicketController::class, 'store']);
    Route::put('/teknik-get/{id}', [TeknikTicketController::class, 'update']);
    Route::delete('/teknik-get/{id}', [TeknikTicketController::class, 'destroy']);

    Route::get('/options', [OptionController::class, 'getOptions']);
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    Route::get('/dashboard/trend', [DashboardController::class, 'trend']);

    Route::post('/ai-chat', [ChattController::class, 'chat']);
    Route::get('/ai/export-excel', [ExportController::class, 'export']);
    Route::get('/ai/analisis-user', [AiAnalysisController::class, 'user']);
    Route::get('/ai/analisis-tiket', [AiAnalysisController::class, 'tiket']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->prefix('agent')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardAdminController::class, 'index']);

    // Tiket CRUD (ambil, update, notes, progress)
    Route::get('/tickets', [TiketController::class, 'index']);
    Route::get('/tickets/{id}', [TiketController::class, 'show']);
    Route::post('/tickets/{id}/take', [TiketController::class, 'takeTicket']);
    Route::put('/tickets/{id}', [TiketController::class, 'update']);
    Route::post('/tickets/{id}/notes', [TiketController::class, 'addNote']);
    Route::post('/tickets/{id}/progress', [TiketController::class, 'addProgress']);
});

