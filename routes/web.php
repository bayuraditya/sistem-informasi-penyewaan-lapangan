<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CourtController;
use App\Http\Controllers\ReservationController;
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



Route::middleware(['guest'])->group(function () {
    Route::get('/', function () {
        return view('guest');
    }); 
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
});



Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin', [AdminController::class, 'index']);
        
        Route::prefix('admin')->group(function () {
            Route::get('/courts', [CourtController::class, 'index'])->name('courts.index');
            Route::get('/courts/create', [CourtController::class, 'create'])->name('courts.create');
            Route::post('/courts', [CourtController::class, 'store'])->name('courts.store');
            Route::get('/courts/{court}', [CourtController::class, 'show'])->name('courts.show');//ini keknya gaperlu
            Route::get('/courts/{court}/edit', [CourtController::class, 'edit'])->name('courts.edit');
            Route::put('/courts/{court}', [CourtController::class, 'update'])->name('courts.update');
            Route::delete('/courts/{court}', [CourtController::class, 'destroy'])->name('courts.destroy');
    
        });
        
    });

    Route::middleware(['role:customer'])->group(function () {
        Route::get('/home', [ReservationController::class, 'index'])->name('home');
        Route::get('/available-courts', [ReservationController::class, 'available_courts']);
        Route::post('/book', [ReservationController::class, 'store'])->name('reservation.store');
        Route::post('/booking-detail', [ReservationController::class, 'booking_detail'])->name('booking_detail');
        
    });

});

