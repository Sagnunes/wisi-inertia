<?php

use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePermissionController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->prefix('administracao')->group(function () {
    Route::resource('perfis', RoleController::class)->names('roles')->parameter('perfis', 'role')->except('create');
    Route::resource('permissoes', PermissionController::class)->names('permissions')->parameter('permissoes', 'permission')->except('create');

    Route::get('perfis/{role}/permissoes', [RolePermissionController::class, 'edit'])->name('roles.permissions.edit');
    Route::patch('perfis/{role}/permissoes', [RolePermissionController::class, 'update'])->name('roles.permissions.update');
});
require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
