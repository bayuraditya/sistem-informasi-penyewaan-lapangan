<?php

namespace App\Http\Controllers;
date_default_timezone_set('Asia/Shanghai');

use App\Models\Court;
use App\Models\RentalSession;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Reservation;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use Illuminate\Support\Facades\Auth;

class ExportController extends Controller
{
    //klik button cetak - muncul modal, pilih durasi tanggal, klik tombol cetak laporan, download, selesai.
    public function exportTransaction(Request $request){
        $today = new DateTime();
        $today = $today->format('Y-m-d');
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $queryEndDate = $endDate . "T23:59" ;

        if($startDate == null){
            // 
            $startDate ="0" ;
        }
        
        if($endDate == null){
            $queryDate = $today . "T23:59";
            $endDate = $today;
        }

     
        $transactions = Transaction::with(['user', 'reservations.court', 'reservations.rentalSession'])
        ->when($startDate && $endDate, function ($query) use ($startDate, $queryEndDate) {
            $query->whereBetween('created_at', [$startDate, $queryEndDate]);
        })
        ->get();
        $user = Auth::user();
        $pdf = PDF::loadview('admin.transaction.export',compact('transactions','startDate','endDate','user'));
    	return $pdf->download();

    }

    public function exportReservation(Request $request){
        // dd($request);
        // $today = new DateTime();
        // $today = $today->format('Y-m-d');
        // $startDate = $request->start_date;
        // $endDate = $request->end_date;
        // $queryEndDate = $endDate . "T23:59" ;

        // if($startDate == null){
        //     // 
        //     $startDate ="0" ;
        // }
        
        // if($endDate == null){
        //     $queryDate = $today . "T23:59";
        //     $endDate = $today;
        // }

     
    
        
        $user = Auth::user();
        $reservations = Reservation::with('court', 'user', 'rentalSession', 'transactions')
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

        $pdf = PDF::loadview('admin.reservation.export',compact('today','court','allCourt','reservations','rentalSession','date','user'));
    	return $pdf->download();

    }

}
