<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Court;
use Illuminate\Support\Facades\Auth;
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
        $date = $request->input('date');
        $court = $request->input('court');
        $reservations = Reservation::where('start_time', 'like', '%' .$date.'%')
        ->where('court_id', $court)
        ->get();

        // $reservation = Reservation::all();
        return view('customer.available-courts', compact('reservations','date'));

    }


    public function create()
    {
        // Logika untuk menampilkan formulir pembuatan reservasi
    }

    public function store(Request $request)
    {
        // Logika untuk menyimpan reservasi baru
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
