<?php

namespace App\Http\Controllers;
date_default_timezone_set('Asia/Shanghai');

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Court;
use App\Models\RentalSession;
use App\Models\Transaction;
use App\Models\ReservationTransaction;
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

    public function book(Request $request)
    {
        $rentalSession = RentalSession::all();

    //   dd($request->input('rental_session_time'));
        $reservation = new Reservation;
        $reservation->user_id=Auth::user()->id;
        $reservation->rental_session_times = $request->rental_session_times;
        $reservation->court_id = $request->court;
        $reservation->date = $request->date;
        return view('customer.booking-detail', compact('reservation','rentalSession'));

            // return redirect()->route('booking_detail');
            
    }
    public function booking_detail(Request $request)
    {
        //tess
    }
    public function store(Request $request){
    // create reservasi -> create transaksi -> each sesi,attach -> baru bisa snap bayar
        // $reservation = new Reservation;
        // $reservation->user_id=Auth::user()->id;
        // $reservation->rental_session_times = $request->rental_session_times;
        // $reservation->court_id = $request->court;
        // $reservation->date = $request->date;
       
        date_default_timezone_set('Asia/Shanghai');
        $date = $request->input('date');
        $today = new DateTime();
        $today = $today->format('Y-m-d');

        $transaction = new Transaction;
        $transaction->user_id=Auth::user()->id;
        $transaction->total_amount = sizeof($request->rental_session_times)*40000;
        $transaction->save();
        $transaction_id = $transaction->id;

        foreach($request->rental_session_times as $s){
            $reservation = new Reservation;

            $reservation->user_id=Auth::user()->id;
            $reservation->rental_session_id = $s;
            $reservation->court_id = $request->court;
            $reservation->date = $request->date;
            $reservation->save();
            $reservation->transactions()->attach($transaction_id);
        }
        $reservation->rental_session_times = $request->rental_session_times;

        
        //payment gateway here
        
       return view('customer.checkout', compact('reservation','transaction'));
    }

    public function pay(Request $request){
        // $request->request->add(['total_price' => $request->qty * 100, 'status' => 'Unpaid']);
        // $order = Order::create($request->all());
             // Set your Merchant Server Key
             \Midtrans\Config::$serverKey = config('midtrans.server_key');
             // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
             \Midtrans\Config::$isProduction = false;
             // Set sanitization on (default)
             \Midtrans\Config::$isSanitized = true;
             // Set 3DS transaction for credit card to true
             \Midtrans\Config::$is3ds = true;
           dd($request);
             //  $params = array(
            //      'transaction_details' => array(
            //          'order_id' => $order->id,
            //          'gross_amount' => $order->total_price,
            //      ),
            //      'customer_details' => array(
            //          'first_name' => $request->name ,
            //          'last_name' => '',
            //          'phone' => $request->phone,
            //      ),
            //  );
            //  $snapToken = \Midtrans\Snap::getSnapToken($params); 
             return view('checkout', compact('snapToken','order'));
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
