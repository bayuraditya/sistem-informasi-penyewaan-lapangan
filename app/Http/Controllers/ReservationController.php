<?php

namespace App\Http\Controllers;
date_default_timezone_set('Asia/Shanghai');

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Court;
use App\Models\RentalSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use DateTime;
use DateInterval;
/*
    /home
    /cek lapangan
    /cek lapangan/tgl 
    /book
    /checkout
    /detail order
     */

class ReservationController extends Controller
{
    public function index()
    {
        // $user = Auth::user();
        // $reservations = Reservation::where('user_id', $user->id)->get();
        $court = Court::all();
        return view('customer.home', compact('court'));


    }
    public function available_courts(Request $request){
        date_default_timezone_set('Asia/Shanghai');
        $date = $request->input('date');

        $court = $request->input('court');
        $reservations = Reservation::where('date', 'like', '%' .$date.'%')
        ->where('court_id', $court)
        ->get();

        // dd($reservations);
        $today = new DateTime();
        $yesterday = $today->modify('-1 day');
        $yesterdayString = $yesterday->format('Y-m-d');

        if($date <= $yesterdayString){
            return redirect()->route('home');
        }else{
            return view('customer.available-courts', compact('reservations','date','court'));
        }
    }

    public function create()
    {
        // Logika untuk menampilkan formulir pembuatan reservasi
    }

    public function store(Request $request)
    {
        $rentalSession = RentalSession::all();

    //   dd($request->input('rental_session_time'));
        $reservation = new Reservation;
        $reservation->user_id=Auth::user()->id;
        $reservation->rental_session_times = $request->input('rental_session_time');
        $reservation->court_id = $request->court;
        $reservation->date = $request->date;
        // foreach($rental_session_times as $rental_session_time){
        //  $reservation->rental_session_id =$rental_session_time;
        //  $reservation->save();   
        //  $reservation->transactions()->attach($rental_session_time);
        // }
        // return redirect()->route('booking_detail');
        // return view('customer.booking-detail', compact('reservation','rental_session_times'));
        return view('customer.booking-detail', compact('reservation','rentalSession'));

            // return redirect()->route('booking_detail');
            
    }
    public function booking_detail(Request $request)
    {
        
    }
    public function show($id)
    {
        $reservation = Reservation::find($id);
        return view('reservations.show', ['reservation' => $reservation]);
    }

    public function edit($id)
    {
        $reservation = Reservation::find($id);
        return view('reservations.edit', ['reservation' => $reservation]);
    }

    public function update(Request $request, $id)
    {
        $reservation = Reservation::find($id);

        // Validasi reservasi
        if (!$reservation || $reservation->user_id !== Auth::id()) {
            return redirect()->route('reservations.index')->with('error', 'Reservasi tidak ditemukan atau Anda tidak memiliki izin.');
        }

        $validatedData = $request->validate([
            'start_time' => 'required|date|after_or_equal:now',
            'end_time' => 'required|date|after:start_time',
            // ...validasi lainnya sesuai kebutuhan
        ]);

        $reservation->start_time = $validatedData['start_time'];
        $reservation->end_time = $validatedData['end_time'];
        // Update atribut lainnya sesuai kebutuhan

        $reservation->save();

        return redirect()->route('reservations.show', $reservation->id)->with('success', 'Reservasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $reservation = Reservation::find($id);

        if (!$reservation || $reservation->user_id !== Auth::id()) {
            return redirect()->route('reservations.index')->with('error', 'Reservasi tidak ditemukan atau Anda tidak memiliki izin.');
        }

        $reservation->delete();

        return redirect()->route('reservations.index')->with('success', 'Reservasi berhasil dihapus.');
    }
}
