<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\FlightController;


Route::get('/', [UserController::class, 'index']);
Route::get('/category/{id}', [UserController::class, 'show'])->name('category.show');


Route::get('/login', function () {
    return view('auth.login');
});

Auth::routes();
Route::get('/home', [App\Http\Controllers\StudentController::class, 'index'])->name('home');

Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/passengers', [UserController::class, 'getUsers'])->name('passengers.index');

});


Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    Route::post('/categories/store', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::put('/categories/update/{id}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/categories/delete/{id}', [CategoryController::class, 'destroy'])->name('admin.categories.delete');

});




Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    // Flights
    Route::get('/flights', [FlightController::class, 'index'])->name('admin.flights.index');
    Route::get('/flights/create', [FlightController::class, 'create'])->name('admin.flights.create');
    Route::post('/flights/store', [FlightController::class, 'store'])->name('admin.flights.store');

    Route::get('/flights/edit/{id}', [FlightController::class, 'edit'])->name('admin.flights.edit');
    Route::post('/flights/update/{id}', [FlightController::class, 'update'])->name('admin.flights.update');

    Route::delete('/flights/delete/{id}', [FlightController::class, 'destroy'])->name('admin.flights.delete');

});


