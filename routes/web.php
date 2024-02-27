<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CourtController;
use App\Http\Controllers\ReservationController;
use App\Models\Reservation;

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
            Route::prefix('court')->group(function(){
                Route::get('/', [CourtController::class, 'index'])->name('courts.index');
                Route::post('/create', [CourtController::class, 'create'])->name('court.create');
                Route::post('/store', [CourtController::class, 'store'])->name('court.store');
                Route::get('/{id}', [CourtController::class, 'show'])->name('court.show');//ini keknya gaperlu
                Route::get('/{id}', [CourtController::class, 'edit'])->name('court.edit');
                Route::put('/{id}', [CourtController::class, 'update'])->name('court.update');
                Route::delete('/{id}', [CourtController::class, 'destroy'])->name('court.destroy');
                
            });
         
            Route::get('/reservations', [AdminController::class, 'reservation']);
            

        });
        
    });

    Route::middleware(['role:customer'])->group(function () {
        Route::get('/home', [ReservationController::class, 'index'])->name('home');
        Route::get('/available-courts', [ReservationController::class, 'available_courts']);
        Route::post('/book', [ReservationController::class, 'book'])->name('reservation.book');
        Route::post('/checkout', [ReservationController::class, 'store'])->name('reservation.checkout');
        Route::post('/pay', [ReservationController::class, 'pay'])->name('reservation.pay');
        // Route::post('/checkout', [ReservationController::class, 'checkout'])->name('checkout');
        // Route::post('/checkout', [ReservationController::class, 'checkout']);
        Route::get('/invoice/{id}',[ReservationController::class, 'invoice']);
    });

});

