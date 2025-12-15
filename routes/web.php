<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\User\CatalogController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\User\TradeController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// User catalog routes - public (tidak butuh login)
Route::prefix('user')->name('user.')->group(function () {
    Route::get('/catalog', [CatalogController::class, 'index'])->name('index');
    // Public pages
    // Protected user-only pages
    Route::middleware(['auth', 'isUser'])->group(function(){
        // Trade page
        Route::get('/trade', [TradeController::class, 'index'])->name('trade');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/profil', [DashboardController::class, 'tampilProfil'])->name('profil');
        Route::put('/profil', [DashboardController::class, 'updateProfil']);
        Route::get('/ganti-password', [DashboardController::class, 'tampilGantiPassword'])->name('ganti-password');
        Route::post('/ganti-password', [DashboardController::class, 'updateGantiPassword'])->name('ganti-password');

        // Admin user management
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/active', [UserController::class, 'active'])->name('active');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{user}', [UserController::class, 'show'])->name('show');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::patch('/{user}/activate', [UserController::class, 'activate'])->name('activate');
            Route::patch('/{user}/deactivate', [UserController::class, 'deactivate'])->name('deactivate');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        });

        // Items management: browse/index (list)
        Route::get('/items', [ItemController::class, 'index'])->name('items.index');

        // Items management: create/store items (skins)
        Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
        Route::post('/items', [ItemController::class, 'store'])->name('items.store');
        // edit, update, delete
        Route::get('/items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
        // item show/detail must come after '/items/{item}/edit' to avoid route conflicts with 'create'
        Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');
        Route::put('/items/{item}', [ItemController::class, 'update'])->name('items.update');
        Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');
    });
});
