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
use Psy\Readline\Transient;

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

        //reset pending ke exp yg tidak payment
        $now = new DateTime();
        $nowDate = $now->format('Y-m-d H:i:s');
        $transactionCreated = Transaction::Where('transactions.payment_status', '=', 'pending')->pluck('created_at');
        foreach($transactionCreated as $tr){
            // $transaction = Transaction::where('created_at', $tr)->get();
            $transactionId = Transaction::where('created_at', $tr)->value('id');
            $expireTime = date('Y-m-d H:i:s', strtotime($tr . '+15 minutes'));
            // echo $now . '<br>'. $expireTime . '<br>';
            if($expireTime < $nowDate){
                $transactionData = [
                    'payment_status' => 'expire'
                ];
                $transaction = Transaction::find($transactionId);;
                $transaction->update($transactionData);
                
            }
        }
        // 

        date_default_timezone_set('Asia/Shanghai');
        $date = $request->input('date');

        $court = $request->input('court');
        $reservations = Reservation::where('date', 'like', '%' .$date.'%')
        ->where('court_id', $court)
        ->get();

        $unavailableReservations = Reservation::join('reservation_transaction', 'reservations.id', '=', 'reservation_transaction.reservation_id')
        ->join('transactions', 'reservation_transaction.transaction_id', '=', 'transactions.id')
        ->select('reservations.*', 'transactions.*')
        ->where('reservations.date', '=', $date)
        ->where('reservations.court_id', '=', $court)
        ->where(function($query) {
            $query->where('transactions.payment_status', '=', 'settlement')
                  ->orWhere('transactions.payment_status', '=', 'pending')
                  ->orWhere('transactions.payment_status', '=', 'capture');
        })
        ->get();


        $today = new DateTime();
        $yesterday = $today->modify('-1 day');
        $yesterdayString = $yesterday->format('Y-m-d');

        if($date <= $yesterdayString){
            return redirect()->route('home');
        }else{
            return view('customer.available-courts', compact('unavailableReservations','reservations','date','court'));
        }
    }

    //book detail - save ke db return routes? -> selesaikan pembayaran
    public function create()
    {
        // Logika untuk menampilkan formulir pembuatan reservasi
    }

    public function book(Request $request)
    {
        $rentalSession = RentalSession::all();
        $reservation = new Reservation;
        $reservation->user_id=Auth::user()->id;
        $reservation->rental_session_times = $request->rental_session_times;
        $reservation->court_id = $request->court;
        $reservation->date = $request->date;
        
        return view('customer.booking-detail', compact('reservation','rentalSession'));
    }
    public function booking_detail(Request $request)
    {
        //tess
    }
    public function store(Request $request){
        date_default_timezone_set('Asia/Shanghai');
        $date = $request->input('date');
        $todayDateTime = new DateTime();
        $today = $todayDateTime->format('Y-m-d');
        $now = $todayDateTime->format('Y-m-d H:i:s');
       
        $transaction = new Transaction;
        $transaction->user_id=Auth::user()->id;
        $transaction_id =  $transaction->user_id . strtotime($now);
        $transaction->id = intval($transaction_id);
        $transaction->total_amount = sizeof($request->rental_session_times);
        $transaction->save();
        // ubah transaction id jadi kode unik ?

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

        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        // \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isProduction = true;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;
        $orderDetails = array(
            'transaction_details' => array(
                'order_id' => $transaction_id,
                'gross_amount' => $transaction->total_amount,
            ),
            'customer_details' => array(
                'first_name' => Auth::user()->name,
                'last_name' => '',
                'phone' => Auth::user()->handphone_number,
            ),
        );
        $snapToken = \Midtrans\Snap::getSnapToken($orderDetails); 
        return view('customer.checkout', compact('reservation','transaction','snapToken'));
    }

    public function invoice($id){
       $transaction = Transaction::find($id);
       
       return view('customer.invoice',compact('transaction')); 
    }

    public function callback(Request $request){
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id.$request->status_code.$request->gross_amount.$serverKey);
        // return response()->json($request->signature_key);
        // $transaction_time = $request->transaction_time;
        // $settlement_time = $request->settlement_time;
       
        if($hashed == $request->signature_key){
            $transaction = Transaction::find($request->order_id);
            // $transaction->update($transactionData);
            $transaction->payment_method = $request->payment_type;
            $transaction->payment_status = $request->transaction_status;
            $transaction->transaction_time = $request->transaction_time;
            $transaction->settlement_time = $request->settlement_time;
            $transaction->save();
            // if($request->transaction_status == 'capture' or $request->transaction_status == 'settlement' ){
            //     $transaction = Transaction::find($request->order_id);
            //     $transaction->update(['payment_status' => $request->transaction_status]);
            // }
        }

        return response()->json($transaction);
    }
// 
// 
// 
// 
    public function recurring(Request $request){
        $status = 'recurring';
        return response()->json($status);
    }
    public function pay_account_notification(Request $request){
        $status = 'pay_account_notification';
        return response()->json($status);
    }
    public function finish(Request $request){
        $status = 'finish';
        return response()->json($status);
    }
    public function unfinish(Request $request){
        $status = 'unfinish';
        return response()->json($status);
    }
  
    public function error(Request $request){
        $status = 'error';
        return response()->json($status);
    }
  
    // 
    // 
    // 
    // 
    // 
    // 


    public function show()
    {
       
        $reservations = Reservation::with('court', 'user', 'rentalSessions', 'transactions')
        ->join('courts', 'reservations.court_id', '=', 'courts.id')
        ->join('users', 'reservations.user_id', '=', 'users.id')
        ->join('rental_sessions', 'reservations.rental_session_id', '=', 'rental_sessions.id')
        ->join('reservation_transaction', 'reservations.id', '=', 'reservation_transaction.reservation_id')
        ->join('transactions', 'reservation_transaction.transaction_id', '=', 'transactions.id')
        ->select('reservations.*', 'courts.*', 'users.*', 'rental_sessions.*', 'transactions.*')
        ->get();

        return view('admin.reservation', ['reservation' => $reservations]);
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
