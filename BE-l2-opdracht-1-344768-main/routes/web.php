<?php

use App\Http\Controllers\LeverancierController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return view('home');
    }

    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware('auth')->name('dashboard');

Route::middleware(['auth', 'can:access-medewerker-dashboard'])->group(function () {
    Route::get('/products', [ProductController::class, 'index'])
        ->name('products.index');

    Route::get('/products/{product}/allergenen', [ProductController::class, 'allergenen'])
        ->name('products.allergenen');

    Route::get('/products/{product}', [ProductController::class, 'show'])
        ->name('products.show');

    Route::get('/leveranciers', [LeverancierController::class, 'index'])
        ->name('leveranciers.index');

    Route::get('/leveranciers/{leverancier}/producten', [LeverancierController::class, 'producten'])
        ->name('leveranciers.producten');
});

require __DIR__.'/auth.php';
