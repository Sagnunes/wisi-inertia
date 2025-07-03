<?php

use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('administracao')->group(function () {
    Route::resource('perfis', RoleController::class)->except('create');
});
require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
