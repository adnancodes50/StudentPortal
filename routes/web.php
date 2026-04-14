<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\FlightController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\Admin\HotelController;


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
    Route::put('/flights/update/{id}', [FlightController::class, 'update'])->name('admin.flights.update');

    Route::delete('/flights/delete/{id}', [FlightController::class, 'destroy'])->name('admin.flights.delete');


     Route::get('/banks', [BankController::class, 'index'])->name('admin.banks.index');

    Route::post('/banks/store', [BankController::class, 'store'])->name('admin.banks.store');

    Route::put('/banks/update/{id}', [BankController::class, 'update'])->name('admin.banks.update');

    Route::delete('/banks/delete/{id}', [BankController::class, 'destroy'])->name('admin.banks.delete');

    Route::get('/hotels', [HotelController::class, 'index'])->name('admin.hotels.index');
Route::post('/hotels/store', [HotelController::class, 'store'])->name('admin.hotels.store');
Route::put('/hotels/update/{id}', [HotelController::class, 'update'])->name('admin.hotels.update');
Route::delete('/hotels/delete/{id}', [HotelController::class, 'destroy'])->name('admin.hotels.delete');
});


