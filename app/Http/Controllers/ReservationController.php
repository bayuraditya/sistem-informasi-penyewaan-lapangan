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
use Illuminate\Support\Arr;
use Psy\Readline\Transient;

class ReservationController extends Controller
{
    public function index(){
        $todayDateTime = new DateTime();
        $todayDate = $todayDateTime->format('Y-m-d');
        $user = Auth::user();
        return view('customer.home', compact('user', 'todayDate'));
    }

    public function availableCourts(Request $request){
        $this->updatePendingPayment();
        date_default_timezone_set('Asia/Shanghai');
        $date = $request->input('date');
        $court = $request->input('court');

        $allCourt = Court::all();
        $rentalSessions = RentalSession::all();
        $reservations = Reservation::where('date', 'like', '%' . $date . '%')
            ->where('court_id', $court)
            ->get();

        $unavailableReservations = Reservation::join('reservation_transaction', 'reservations.id', '=', 'reservation_transaction.reservation_id')
            ->join('transactions', 'reservation_transaction.transaction_id', '=', 'transactions.id')
            ->select('reservations.*', 'transactions.*')
            ->where('reservations.date', '=', $date)
            ->where(function ($query) {
                $query->where('transactions.payment_status', '=', 'settlement')
                    ->orWhere('transactions.payment_status', '=', 'pending')
                    ->orWhere('transactions.payment_status', '=', 'capture');
            })
            ->get();

        $today = new DateTime();
        $yesterday = $today->modify('-1 day');
        $yesterdayString = $yesterday->format('Y-m-d');

        $user = Auth::user();
         if ($date <= $yesterdayString) {
            return redirect()->route('home');
        } else {
            return view('customer.available-courts', compact('unavailableReservations', 'reservations', 'date', 'court', 'allCourt', 'rentalSessions', 'user'));
        }
        // return view('customer.available-courts', compact('unavailableReservations', 'reservations', 'date', 'court', 'allCourt', 'rentalSessions', 'user'));
    }

    public function book(Request $request){
        
        $rentalSession = RentalSession::all();
        $allCourt = Court::all();
        $order = array();
        $order['user_id'] = Auth::user()->id;
        $order['date'] = $request->date;
        $order['reservation'] = $request->reservation;
        if ($request->has('reservation')) {
            foreach ($order['reservation'] as $courtId => $sesiId) {
                        foreach ($sesiId as $key => $value) {
                            $reservation['court'][$courtId]['sesi'][$value] = [
                                'time' => RentalSession::where('id', $value)->select('rental_session_time')->first()['rental_session_time'],
                                'price' => RentalSession::where('id', $value)->select('price')->first()['price']
                            ];
                            $reservation['court'][$courtId]['court_name'] = Court::find($courtId)['court_name'];
                        }
                    }
        } else {
            return redirect('/available-courts?date='.$request->date);
        }
        $user = Auth::user();
        return view('customer.booking-detail', compact('reservation', 'order', 'rentalSession', 'allCourt', 'user'));
    }
    
    public function store(Request $request){
       
        $user = Auth::user();
        date_default_timezone_set('Asia/Shanghai');
        $date = $request->input('date');
        $todayDateTime = new DateTime();
        $today = $todayDateTime->format('Y-m-d');
        $now = $todayDateTime->format('Y-m-d H:i:s');

            $transaction = new Transaction;
            $transaction->user_id = Auth::user()->id;
            $transaction_id = time();
            if (Transaction::where('id', $transaction_id)->exists()) {
                $transaction->id = time();
            } else {
                $transaction->id =  $transaction_id += 1;
            }

            // hitung total sesi untuk menenukan harga
            $totalAmount = 0;
            foreach ($request->reservation as $courtId => $reservation) {
                foreach ($reservation as $index => $rentalSessionId) {
                    $price =  RentalSession::where('id', $rentalSessionId)->select('price')->first()['price'];
                    $sandBoxPrice = 0;
                    // harga sementara selama pengembangan website
                    if ($price == 40000) {
                        $sandBoxPrice = 4;
                    } else if ($price == 50000) {
                        $sandBoxPrice = 5;
                    }
                    $totalAmount += $sandBoxPrice;
                    // $totalAmount += $price;
                }
            }
            //Count sum reservation
            $amountReservation = count($request->reservation);

            $transaction->total_amount = $totalAmount;
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
                    'gross_amount' => $totalAmount,
                ),
                'customer_details' => array(
                    'first_name' => Auth::user()->name,
                    'last_name' => '',
                    'phone' => Auth::user()->handphone_number,
                ),
                // 'item_details' => array(
                //     array(
                //         'id' => $transaction_id,
                //         'price' => $totalAmount/=2,
                //         'quantity' => $amountReservation,
                //         'name' => 'Sesi Lapangan',
                //     ),
                // ),
            );
            $order = $request;
            $snapToken = \Midtrans\Snap::getSnapToken($orderDetails);
            $transaction->snapToken = $snapToken;
            $transaction->save();
            // transaction id blm ngurut
            foreach ($request->reservation as $courtId => $reservation) {
                foreach ($reservation as $index => $rentalSessionId) {
                    $reservation = new Reservation;
                    $reservation->user_id = Auth::user()->id;
                    $reservation->rental_session_id = $rentalSessionId;
                    $reservation->court_id = $courtId;
                    $reservation->date = $request->date;
                    $reservation->note = $request->note;
                    $reservation->save();
                    $reservation->transactions()->attach($transaction_id);
                }
            }
            $order['reservation'] = $request->reservation;
            foreach ($order['reservation'] as $courtId => $sesiId) {
                foreach ($sesiId as $key => $value) {
                    $reservationDetail['court'][$courtId]['sesi'][$value] = [
                        'time' => RentalSession::where('id', $value)->select('rental_session_time')->first()['rental_session_time'],
                        'price' => RentalSession::where('id', $value)->select('price')->first()['price']
                    ];
                    $reservationDetail['court'][$courtId]['court_name'] = Court::find($courtId)['court_name'];
                }
            }

         
            $rentalSession = RentalSession::all();
            return view('customer.checkout', compact('rentalSession', 'order', 'transaction', 'snapToken', 'reservationDetail', 'user'));
           
    }
    public function checkoutPayment($snapToken){
        
        // dd($snapToken);
        return view('customer.checkout', compact('rentalSession', 'order', 'transaction', 'snapToken', 'reservationDetail', 'user'));

    }
    public function invoice($id){
        $transaction = Transaction::find($id);
        $user = Auth::user();
        return view('customer.invoice', compact('user', 'transaction'));
    }

    public function callback(Request $request){
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            $transaction = Transaction::find($request->order_id);
            $transaction->payment_method = $request->payment_type;
            $transaction->payment_status = $request->transaction_status;
            $transaction->transaction_time = $request->transaction_time;
            $transaction->settlement_time = $request->settlement_time;
            $transaction->save();
        }
        return response()->json($transaction);
    }

}
// 