<?php

namespace App\Http\Controllers;
date_default_timezone_set('Asia/Shanghai');
use DateTime;
use App\Models\Court;
use App\Models\RentalSession;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

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
        // dd($request->date);
        $reservations = Reservation::with('court', 'user', 'rentalSessions', 'transactions')
        ->join('courts', 'reservations.court_id', '=', 'courts.id')
        ->join('users', 'reservations.user_id', '=', 'users.id')
        ->join('rental_sessions', 'reservations.rental_session_id', '=', 'rental_sessions.id')
        ->join('reservation_transaction', 'reservations.id', '=', 'reservation_transaction.reservation_id')
        ->join('transactions', 'reservation_transaction.transaction_id', '=', 'transactions.id')
        ->where('transactions.payment_status', 'settlement') // Menambahkan kondisi payment_status
        ->where('reservations.date', $request->date) 
        ->where('reservations.court_id', $request->court_id) 
        ->orWhere('transactions.payment_status', 'capture')
        ->select('reservations.*', 'courts.*', 'users.*', 'rental_sessions.*', 'transactions.*')
        ->get();

        $now = new DateTime();
        $today = $now->format('Y-m-d');
        $court = Court::orderBy('id', 'asc')->first();
        $allCourt = Court::all();
        $rentalSession = RentalSession::all();
        return view('admin.reservation',compact('today','court','allCourt','reservations','rentalSession'));
    }

    // public function search_reservation(Request $request){
    //     $court = $request->court;
    //     $date = $request->date;
        
    //     return view('admin.reservation',compact('court','date'));

    // }

}
