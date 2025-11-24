<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AgentController;

/*
|--------------------------------------------------------------------------
| Public Routes (Login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.process');
});

/*
|--------------------------------------------------------------------------
| Redirect Root Otomatis Sesuai Role
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $user = auth()->user();

    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }

    if ($user->hasRole('agent')) {
        return redirect()->route('agent.dashboard');
    }

    if ($user->hasRole('user')) {
        return redirect()->route('user.dashboard');
    }

    // fallback role tidak dikenal
    auth()->logout();
    return redirect()->route('login')->with('error', 'Role tidak dikenali.');
});


/*
|--------------------------------------------------------------------------
| Admin Area
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Tiket
        Route::get('/tiket', [AdminController::class, 'tiket'])->name('tiket.index');
        Route::get('/monitoring', [AdminController::class, 'monitoring'])->name('monitoring.index');

        // Kelola User (AJAX)
        Route::get('/kelola-user', [AdminController::class, 'kelolaUser'])->name('kelola-user.index');
        Route::get('/kelola-user/data', [AdminController::class, 'getUserData'])->name('kelola-user.data');
        Route::post('/kelola-user', [AdminController::class, 'storeUser']);
        Route::put('/kelola-user/{id}', [AdminController::class, 'updateUser']);
        Route::delete('/kelola-user/{id}', [AdminController::class, 'destroyUser']);
    });


/*
|--------------------------------------------------------------------------
| User / Staff Area
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {

        Route::get('/dashboard', [StaffController::class, 'dashboardUser'])->name('dashboard');
        Route::get('/tiket', [StaffController::class, 'tiketUser'])->name('tiket.index');
    });


/*
|--------------------------------------------------------------------------
| Agent Area
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:agent'])
    ->prefix('agent')
    ->name('agent.')
    ->group(function () {

        Route::get('/dashboard', [AgentController::class, 'dashboard'])->name('dashboard');
        Route::get('/tiket', [AgentController::class, 'tiket'])->name('tiket.index');
        Route::get('/profil', [AgentController::class, 'profil'])->name('profil');
    });


/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/
Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');


/*
|--------------------------------------------------------------------------
| Fallback (404 / Role tidak punya akses)
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return redirect()->route('login')->with('error', 'Halaman tidak ditemukan.');
});
