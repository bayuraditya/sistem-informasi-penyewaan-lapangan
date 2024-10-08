<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CourtController;
use App\Http\Controllers\ExportController;
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

    Route::get('/', [ReservationController::class, 'index'])->name('home');
    Route::get('/price', function () {
        $user = auth()->user();
        return view('customer.price', compact('user'));
    })->name('price');
    Route::get('/available-courts', [ReservationController::class, 'availableCourts'])->name('availableCourts');
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    /* 
    klik lupa password, 
    halaman lupa password masukan email klik send email, 
    halaman email telah dikirim, 
    di email klik link /reset-passowrd/{id}, 
    halaman passowrd baru ok, 
    halamanlogin
*/
// halaman lupa password, masukan email
    Route::get('/forgot-password', [CustomerController::class, 'forgotPassword'])->name('forgotPassword');
    //halaman email terkirim
    Route::post('/send-mail', [CustomerController::class, 'sendEmail'])->name('sendEmail');
    // halaman reset password
    Route::get('/reset-password/{id}', [CustomerController::class, 'resetPassword'])->name('resetPassword');
    //reset password baru
    Route::put('/new-password/{id}', [CustomerController::class, 'newPassword'])->name('newPassword');
           
});



Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.home');
        Route::prefix('admin')->group(function () {
            Route::prefix('court')->group(function () {
                Route::get('/', [CourtController::class, 'index'])->name('courts.index');
                Route::post('/create', [CourtController::class, 'create'])->name('court.create');
                Route::post('/store', [CourtController::class, 'store'])->name('court.store');
                Route::get('/{id}', [CourtController::class, 'show'])->name('court.show'); //ini keknya gaperlu
                Route::get('/{id}', [CourtController::class, 'edit'])->name('court.edit');
                Route::put('/{id}', [CourtController::class, 'update'])->name('court.update');
                Route::delete('/{id}', [CourtController::class, 'destroy'])->name('court.destroy');
            });
            Route::prefix('reservation')->group(function () {
                Route::get('/', [AdminController::class, 'reservation'])->name('reservation.index');
                
                Route::get('/select-date', [AdminController::class, 'selectDate'])->name('admin.reservation.home');
                Route::get('/available-courts', [AdminController::class, 'availableCourts'])->name('admin.availableCourts');
                Route::post('/book', [AdminController::class, 'book'])->name('admin.reservation.book');
                Route::post('/checkout', [AdminController::class, 'store'])->name('admin.reservation.checkout');
                Route::put('/cancel/{id}', [CustomerController::class, 'cancel'])->name('admin.reservation.cancel');
                Route::get('/invoice/{id}', [AdminController::class, 'invoice']);
                Route::get('/error?order_id={id}', [AdminController::class, 'error']);
              
                Route::post('/export', [ExportController::class, 'exportReservation']);
        
            });
            Route::get('/profit', [AdminController::class, 'profit'])->name('profit');
            Route::prefix('transaction')->group(function () {
                Route::get('/', [AdminController::class, 'transaction'])->name('transaction.index');
                Route::post('/export', [ExportController::class, 'exportTransaction']);
                Route::delete('/destroy/{id}', [AdminController::class, 'deleteReservationTransaction'])->name('transaction.destroy');
            });
            Route::prefix('user')->group(function () {
                Route::get('/', [AdminController::class, 'allUser'])->name('user.index');
                Route::get('/{id}', [AdminController::class, 'userDetail']);
            });
            Route::prefix('profile')->group(function () {
                Route::get('/', [AdminController::class, 'edit'])->name('admin.edit');
                Route::put('/update/{id}', [AdminController::class, 'update'])->name('admin.update');
                Route::get('/change-password', [AdminController::class, 'showChangePasswordForm'])->name('adminShowChangePasswordForm');
                Route::put('/change-password/{id}', [AdminController::class, 'changePassword'])->name('changePassword');
                Route::get('/reservation-history', [AdminController::class, 'reservationHistory'])->name('reservationHistory');
                Route::get('/transaction-history', [AdminController::class, 'transactionHistory'])->name('transactionHistory');
            });
        });
    });

    Route::middleware(['role:customer'])->group(function () {
        // Route::get('/home', [ReservationController::class, 'index'])->name('home');
        Route::post('/book', [ReservationController::class, 'book'])->name('reservation.book');
        Route::post('/checkout', [ReservationController::class, 'store'])->name('reservation.checkout');
        Route::post('/checkout-payment', [ReservationController::class, 'checkoutPayment'])->name('reservation.checkoutPayment');
        Route::put('/cancel/{id}', [CustomerController::class, 'cancel'])->name('reservation.cancel');
        Route::get('/invoice/{id}', [ReservationController::class, 'invoice']);
        Route::get('/error?order_id={id}', [ReservationController::class, 'error']);

        Route::prefix('profile')->group(function () {
            Route::get('/', [CustomerController::class, 'edit'])->name('Customer.edit');
            Route::put('/update/{id}', [CustomerController::class, 'update'])->name('Customer.update');
            Route::get('/change-password', [CustomerController::class, 'showChangePasswordForm'])->name('showChangePasswordForm');
            Route::put('/change-password/{id}', [CustomerController::class, 'changePassword'])->name('changePassword');
            Route::get('/reservation-history', [CustomerController::class, 'reservationHistory'])->name('reservationHistory');
            Route::get('/transaction-history', [CustomerController::class, 'transactionHistory'])->name('transactionHistory');
            Route::put('/reschedule', [CustomerController::class, 'reschedule'])->name('reschedule');

        });
    });
});
