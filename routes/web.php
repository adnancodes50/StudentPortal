<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUsersController;
use App\Http\Controllers\Admin\AdminBookingsController;
use App\Http\Controllers\Admin\AdminPaymentsController;
use App\Http\Controllers\Admin\AdminWalletController;
use App\Http\Controllers\Admin\AdminUmrahPackagesController;
use App\Http\Controllers\Admin\AdminUmrahBookingsController;
use App\Http\Controllers\Admin\AdminVisaController;
use App\Http\Controllers\Admin\AdminGroupsController;
use App\Http\Controllers\Admin\AdminInsuranceController;
use App\Http\Controllers\Admin\AdminAgentReportController;
use App\Http\Controllers\Admin\ImpersonationController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\FlightController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\Admin\HotelController;
use App\Http\Controllers\Agent\AgentDashboardController;
use App\Http\Controllers\Customer\UserDashboardController;

Route::get('/', [UserController::class, 'index']);
Route::get('/category/{id}', [UserController::class, 'show'])->name('category.show');

Route::get('/login', function () {
    return view('auth.login');
});

Auth::routes();
Route::get('/home', function () {
    $user = auth()->user();
    $role = $user?->role ?? $user?->type;

    return redirect()->to(match ($role) {
        'admin' => '/admin/dashboard',
        'agent' => '/agent/dashboard',
        default => '/user/dashboard',
    });
})->middleware('auth')->name('home');

// Backend logic only (NO UI)
Route::middleware(['auth', 'role:agent'])->get('/agent/dashboard', [AgentDashboardController::class, 'index'])->name('agent.dashboard');
Route::middleware(['auth', 'role:user'])->get('/user/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/passengers', [UserController::class, 'getUsers'])->name('passengers.index');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Impersonation
    Route::post('/impersonate/{user}', [ImpersonationController::class, 'start'])->name('admin.impersonate.start');
    Route::post('/impersonate/exit', [ImpersonationController::class, 'stop'])->name('admin.impersonate.stop');

    // Users + Agents (single module)
    Route::get('/users', [AdminUsersController::class, 'index'])->name('admin.users.index');
    Route::get('/users/data', [AdminUsersController::class, 'data'])->name('admin.users.data');
    Route::post('/users', [AdminUsersController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{user}', [AdminUsersController::class, 'show'])->name('admin.users.show');
    Route::put('/users/{user}', [AdminUsersController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [AdminUsersController::class, 'destroy'])->name('admin.users.delete');
    // keep compatibility route, same screen
    Route::get('/agents', [AdminUsersController::class, 'index'])->name('admin.agents.index');

    // Reports & Analysis
    Route::get('/reports/agents', [AdminAgentReportController::class, 'index'])->name('admin.reports.agents.index');
    Route::get('/reports/agents/data', [AdminAgentReportController::class, 'data'])->name('admin.reports.agents.data');
    Route::get('/reports/agents/{agent}', [AdminAgentReportController::class, 'show'])->name('admin.reports.agents.show');

    // Bookings
    Route::get('/bookings', [AdminBookingsController::class, 'index'])->name('admin.bookings.index');
    Route::get('/bookings/data', [AdminBookingsController::class, 'data'])->name('admin.bookings.data');

    // Payments
    Route::get('/payments', [AdminPaymentsController::class, 'index'])->name('admin.payments.index');
    Route::get('/payments/data', [AdminPaymentsController::class, 'data'])->name('admin.payments.data');
    Route::post('/payments/{payment}/status', [AdminPaymentsController::class, 'setStatus'])->name('admin.payments.status');

    // Wallet
    Route::get('/wallet', [AdminWalletController::class, 'index'])->name('admin.wallet.index');
    Route::get('/wallet/data', [AdminWalletController::class, 'data'])->name('admin.wallet.data');

    // Umrah
    Route::get('/umrah/packages', [AdminUmrahPackagesController::class, 'index'])->name('admin.umrah.packages.index');
    Route::get('/umrah/packages/data', [AdminUmrahPackagesController::class, 'data'])->name('admin.umrah.packages.data');
    Route::post('/umrah/packages', [AdminUmrahPackagesController::class, 'store'])->name('admin.umrah.packages.store');
    Route::put('/umrah/packages/{package}', [AdminUmrahPackagesController::class, 'update'])->name('admin.umrah.packages.update');
    Route::delete('/umrah/packages/{package}', [AdminUmrahPackagesController::class, 'destroy'])->name('admin.umrah.packages.delete');
    Route::get('/umrah/bookings', [AdminUmrahBookingsController::class, 'index'])->name('admin.umrah.bookings.index');
    Route::get('/umrah/bookings/data', [AdminUmrahBookingsController::class, 'data'])->name('admin.umrah.bookings.data');

    // Placeholder modules
    Route::get('/visa', [AdminVisaController::class, 'index'])->name('admin.visa.index');
    Route::get('/groups', [AdminGroupsController::class, 'index'])->name('admin.groups.index');
    Route::get('/insurance', [AdminInsuranceController::class, 'index'])->name('admin.insurance.index');

    // Existing modules
    Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    Route::post('/categories/store', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::put('/categories/update/{id}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/categories/delete/{id}', [CategoryController::class, 'destroy'])->name('admin.categories.delete');

    Route::get('/flights', [FlightController::class, 'index'])->name('admin.flights.index');
    Route::get('/flights/data', [FlightController::class, 'data'])->name('admin.flights.data');
    Route::get('/flights/create', [FlightController::class, 'create'])->name('admin.flights.create');
    Route::get('/flights/{id}', [FlightController::class, 'show'])->name('admin.flights.show');
    Route::post('/flights/store', [FlightController::class, 'store'])->name('admin.flights.store');
    Route::get('/flights/edit/{id}', [FlightController::class, 'edit'])->name('admin.flights.edit');
    Route::put('/flights/update/{id}', [FlightController::class, 'update'])->name('admin.flights.update');
    Route::post('/flights/{id}/prices', [FlightController::class, 'storePrice'])->name('admin.flights.prices.store');
    Route::put('/flights/{id}/prices/{priceId}', [FlightController::class, 'updatePrice'])->name('admin.flights.prices.update');
    Route::delete('/flights/{id}/prices/{priceId}', [FlightController::class, 'destroyPrice'])->name('admin.flights.prices.delete');
    Route::delete('/flights/delete/{id}', [FlightController::class, 'destroy'])->name('admin.flights.delete');

    Route::get('/banks', [BankController::class, 'index'])->name('admin.banks.index');
    Route::get('/banks/data', [BankController::class, 'data'])->name('admin.banks.data');
    Route::post('/banks/store', [BankController::class, 'store'])->name('admin.banks.store');
    Route::put('/banks/update/{id}', [BankController::class, 'update'])->name('admin.banks.update');
    Route::delete('/banks/delete/{id}', [BankController::class, 'destroy'])->name('admin.banks.delete');

    Route::get('/hotels', [HotelController::class, 'index'])->name('admin.hotels.index');
    Route::get('/hotels/{hotel}/rooms/data', [HotelController::class, 'roomsData'])->name('admin.hotels.rooms.data');
    Route::get('/hotels/{id}', [HotelController::class, 'show'])->name('admin.hotels.show');
    Route::post('/hotels/store', [HotelController::class, 'store'])->name('admin.hotels.store');
    Route::put('/hotels/update/{id}', [HotelController::class, 'update'])->name('admin.hotels.update');
    Route::delete('/hotels/delete/{id}', [HotelController::class, 'destroy'])->name('admin.hotels.delete');
    Route::post('/hotels/{hotel}/rooms', [HotelController::class, 'storeRoom'])->name('admin.hotels.rooms.store');
    Route::delete('/hotels/rooms/{room}', [HotelController::class, 'destroyRoom'])->name('admin.hotels.rooms.delete');
});


