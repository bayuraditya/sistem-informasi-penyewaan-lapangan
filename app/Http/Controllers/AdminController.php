<?php

namespace App\Http\Controllers;
date_default_timezone_set('Asia/Shanghai');
use DateTime;
use App\Models\Court;
use App\Models\RentalSession;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use App\Models\Transaction;
use App\Models\User;

class AdminController extends Controller
{
    public function index(){
        // compact today dan court a
        $now = new DateTime();
        $today = $now->format('Y-m-d');
        $court = Court::orderBy('id', 'asc')->first();
        return view('admin.admin',compact('today','court'));
    }

    public function reservation(Request $request){
        // dd($request);
        $user = Auth::user();
        $reservations = Reservation::with('court', 'user', 'rentalSessions', 'transactions')
        ->join('courts', 'reservations.court_id', '=', 'courts.id')
        ->join('users', 'reservations.user_id', '=', 'users.id')
        ->join('rental_sessions', 'reservations.rental_session_id', '=', 'rental_sessions.id')
        ->join('reservation_transaction', 'reservations.id', '=', 'reservation_transaction.reservation_id')
        ->join('transactions', 'reservation_transaction.transaction_id', '=', 'transactions.id')
        ->where('transactions.payment_status', 'settlement') 
        ->where('reservations.date', $request->date) 
        ->where('reservations.court_id', $request->court_id) 
        ->orWhere('transactions.payment_status', 'capture')
        ->select('reservations.*', 'courts.*', 'users.*', 'rental_sessions.*', 'transactions.*')
        ->get();
        $date = $request->date;
        $now = new DateTime();
        $today = $now->format('Y-m-d');
        $court = Court::orderBy('id', 'asc')->first();
        $allCourt = Court::all();
        $rentalSession = RentalSession::all();
        return view('admin.reservation',compact('today','court','allCourt','reservations','rentalSession','date','user'));
    }

    public function profit(Request $request){
        /*
        keuntungan
        jumlah sesi terbooking

        */
        
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        if($request->startDate == null && $request->endDate == null){
            $filteredProfit=0;
            $filteredTotalBookedSession=0;
        }else{
            $filteredTotalBookedSession = Reservation::join('reservation_transaction', 'reservations.id', '=', 'reservation_transaction.reservation_id')
            ->join('transactions', 'reservation_transaction.transaction_id', '=', 'transactions.id')
            ->whereBetween('reservations.date', [$startDate, $endDate])
            ->whereIn('transactions.payment_status', ['settlement', 'capture'])
            ->count();
            // masih development jadi pakai 1 rupiah. klo sudah jadi ganti 40000
            $filteredProfit = $filteredTotalBookedSession * 1;
        }
        $totalProfit = Transaction::whereIn('payment_status', ['settlement', 'capture'])->sum('total_amount');
       
        // dd($filteredProfit);

        return view('admin.profit',compact('totalProfit','startDate','endDate','filteredProfit','filteredTotalBookedSession'));
    }
    public function transaction(){
    
        $transactions = Transaction::with(['user','reservations.court','reservations.rentalSession'])->get();
        // dd($transactions);

        return view('admin.transactions',compact('transactions'));


    }
}
