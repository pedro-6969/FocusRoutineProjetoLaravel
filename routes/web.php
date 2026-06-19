<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function(){
    Route::get('/dashboard', [TaskController::class, 'index'])->name('dashboard');

    Route::prefix('task')->group(function(){
        Route::post('/', [TaskController::class, 'store'])->name('task.store');
        Route::patch('/{task}/complete', [TaskController::class, 'complete'])->name('task.complete');
        Route::delete('/{task}', [TaskController::class, 'destroy'])->name('task.destroy');
    });

});

require __DIR__.'/auth.php';
