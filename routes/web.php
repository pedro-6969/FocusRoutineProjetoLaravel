<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [TaskController::class, 'index'])
        ->name('dashboard');

    Route::prefix('task')->name('task.')->controller(TaskController::class)->group(function () {
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');

        Route::get('/{task}/edit', 'edit')->name('edit');
        Route::patch('/{task}', 'update')->name('update');

        Route::patch('/{task}/complete', 'complete')->name('complete');
        Route::delete('/{task}', 'destroy')->name('destroy');
    });

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
