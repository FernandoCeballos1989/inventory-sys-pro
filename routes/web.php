<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProviderController;
use App\Models\Provider;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::group(['middleware' => ['auth', 'verified']], function () {

    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('clients', ClientController::class);
    Route::resource('providers',ProviderController::class);
    Route::resource('products',ProductController::class);
});

require __DIR__ . '/settings.php';
