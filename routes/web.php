<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return view('index');
})->name('index');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [TaskController::class, 'dashboard'])
        ->name('dashboard');

    Route::prefix('task')->controller(TaskController::class)->group(function () {
        Route::get('/create', 'create')->name('task.create');
        Route::post('/', 'store')->name('task.store');

        Route::get('/{task}/edit', 'edit')->name('edit');
        Route::patch('/{task}', 'update')->name('update');

        Route::patch('/{task}/complete', 'complete')->name('complete');
        Route::delete('/{task}', 'destroy')->name('destroy');
    });

    Route::prefix('category')->name('category.')->controller(CategoryController::class)->group(function () {
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');

        Route::get('/{category}/edit', 'edit')->name('edit');
        Route::patch('/{category}', 'update')->name('update');

        Route::delete('/{category}', 'destroy')->name('destroy');
    });

    Route::get('/calendar/show', [CalendarController::class, 'show'])
    ->name('calendar.show');

});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
