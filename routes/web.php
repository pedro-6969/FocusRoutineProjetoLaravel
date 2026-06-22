<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\CalendarController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('index');

Route::middleware(['auth', 'verified'])->group(function(){

    Route::get('/dashboard', [TaskController::class, 'dashboard'])->name('dashboard');

    Route::prefix('task')->group(function(){
        Route::get('/create', [TaskController::class, 'create'])->name('task.create');
        Route::post('/store', [TaskController::class, 'store'])->name('task.store');

        Route::get('/{task}/edit', [TaskController::class,'edit'])->name('task.edit');
        Route::patch('/{task', [TaskController::class,'update'])->name('task.update');

        Route::patch('/{task}/complete', [TaskController::class, 'complete'])->name('task.complete');
        Route::delete('/{task}', [TaskController::class, 'destroy'])->name('task.destroy');
    });

    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');

});

require __DIR__.'/auth.php';
