<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Users
    Route::get('/dashboard/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/dashboard/users', [UserController::class, 'store'])->name('users.store');
    Route::post('/dashboard/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/dashboard/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Cars
    Route::get('/dashboard/cars', [CarController::class, 'index'])->name('cars.index');
    Route::get('/dashboard/car/create', [CarController::class, 'create'])->name(name: 'car.create');
    Route::post('/dashboard/car/create', [CarController::class, 'store'])->name(name: 'car.store');
    Route::get('/dashboard/car/edit/{id}', [CarController::class, 'edit'])->name(name: 'car.edit');
    Route::post('/dashboard/car/edit/{id}', [CarController::class, 'update'])->name(name: 'car.update');
    Route::post('/dashboard/car/list-update/{id}', [CarController::class, 'listUpdate'])->name(name: 'car.listupdate');
    Route::get('/dashboard/cars/status/{slug}', [CarController::class, 'showStatus'])->name(name: 'car.showStatus');

    Route::post('/calculate-shipping-cost', [CarController::class, 'calculateShippingCost'])->name('calculate.shipping.cost');
    Route::post('/fetch-locations', [CarController::class, 'fetchLocations'])->name('fetch.locations');
    Route::post('/fetch-ports', [CarController::class, 'fetchPorts'])->name('fetch.ports');
    Route::post('/fetch-from-states', [CarController::class, 'fetchFromStates'])->name('fetch.from.states');

    Route::post('/upload-images', [ImageUploadController::class, 'store'])->name('upload.images.spatie');

});

require __DIR__ . '/auth.php';
