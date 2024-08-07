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
    // public function updatePendingPayment(){
    //      //reset pending ke exp yg tidak payment
    //      $now = new DateTime();
    //      $nowDate = $now->format('Y-m-d H:i:s');
    //      $transactionCreated = Transaction::Where('transactions.payment_status', '=', 'pending')->pluck('created_at');
    //      foreach($transactionCreated as $tr){
    //          // $transaction = Transaction::where('created_at', $tr)->get();
    //          $transactionId = Transaction::where('created_at', $tr)->value('id');
    //          $expireTime = date('Y-m-d H:i:s', strtotime($tr . '+15 minutes'));
    //          // echo $now . '<br>'. $expireTime . '<br>';
    //          if($expireTime < $nowDate){
    //              $transactionData = [
    //                  'payment_status' => 'expire'
    //              ];
    //              $transaction = Transaction::find($transactionId);;
    //              $transaction->update($transactionData);
    //          }
    //      }
    // }
    public function index(){
        // date_default_timezone_set('Asia/Shanghai');
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
            // ->where('reservations.court_id', '=', $court
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
        return view('customer.available-courts', compact('unavailableReservations', 'reservations', 'date', 'court', 'allCourt', 'rentalSessions', 'user'));
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
            // if ($user->role == 'admin') {

            //     date_default_timezone_set('Asia/Shanghai');
            //     $date = $request->input('date');
            //     $todayDateTime = new DateTime();
            //     $today = $todayDateTime->format('Y-m-d');
            //     $now = $todayDateTime->format('Y-m-d H:i:s');

            //     $transaction = new Transaction;
            //     $transaction->user_id = Auth::user()->id;
            //     $transaction_id =  $transaction->user_id . strtotime($now);
            //     $transaction->id = intval($transaction_id);
            //     // hitung total sesi untuk menenukan harga
            //     $totalAmount = 0;
            //     foreach ($request->reservation as $courtId => $reservation) {
            //         foreach ($reservation as $index => $rentalSessionId) {
            //             $price =  RentalSession::where('id', $rentalSessionId)->select('price')->first()['price'];
            //             $sandBoxPrice = 0;
            //             // harga sementara selama pengembangan website
            //             if ($price == 40000) {
            //                 $sandBoxPrice = 1;
            //             } else if ($price == 50000) {
            //                 $sandBoxPrice = 2;
            //             }
            //             $totalAmount += $sandBoxPrice;
            //         }
            //     }
            //     $transaction->total_amount = $totalAmount;
            //     //payment gateway here
            //     \Midtrans\Config::$serverKey = config('midtrans.server_key');
            //     // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
            //     // \Midtrans\Config::$isProduction = false;
            //     \Midtrans\Config::$isProduction = true;
            //     // Set sanitization on (default)
            //     \Midtrans\Config::$isSanitized = true;
            //     // Set 3DS transaction for credit card to true
            //     \Midtrans\Config::$is3ds = true;
            //     $orderDetails = array(
            //         'transaction_details' => array(
            //             'order_id' => '$transaction_id',
            //             'gross_amount' => $transaction->total_amount,
            //         ),
            //         'customer_details' => array(
            //             'first_name' => Auth::user()->name,
            //             'last_name' => '',
            //             'phone' => Auth::user()->handphone_number,
            //         ),
            //     );
            //     $order = $request;
            //     $snapToken = \Midtrans\Snap::getSnapToken($orderDetails);
            //     $transaction->snapToken = $snapToken;
            //     $transaction->save();
            //     // transaction id blm ngurut
            //     foreach ($request->reservation as $courtId => $reservation) {
            //         foreach ($reservation as $index => $rentalSessionId) {
            //             $reservation = new Reservation;
            //             $reservation->user_id = Auth::user()->id;
            //             $reservation->rental_session_id = $rentalSessionId;
            //             $reservation->court_id = $courtId;
            //             $reservation->date = $request->date;
            //             $reservation->note = $request->note;
            //             $reservation->save();
            //             $reservation->transactions()->attach($transaction_id);
            //         }
            //     }
            //     $order['reservation'] = $request->reservation;
            //     foreach ($order['reservation'] as $courtId => $sesiId) {
            //         foreach ($sesiId as $key => $value) {
            //             $reservationDetail['court'][$courtId]['sesi'][$value] = [
            //                 'time' => RentalSession::where('id', $value)->select('rental_session_time')->first()['rental_session_time'],
            //                 'price' => RentalSession::where('id', $value)->select('price')->first()['price']
            //             ];
            //             $reservationDetail['court'][$courtId]['court_name'] = Court::find($courtId)['court_name'];
            //         }
            //     }
            //     // $transaction->snapToken = $snapToken;
            //     // $transaction->save();
            //     $rentalSession = RentalSession::all();
            //     // dd($order);
            //     return view('admin.reservation.index', compact('rentalSession', 'order', 'transaction', 'snapToken', 'reservationDetail', 'user'));
            // } else {
            //     date_default_timezone_set('Asia/Shanghai');
            //     $date = $request->input('date');
            //     $todayDateTime = new DateTime();
            //     $today = $todayDateTime->format('Y-m-d');
            //     $now = $todayDateTime->format('Y-m-d H:i:s');

            //     $transaction = new Transaction;
            //     $transaction->user_id = Auth::user()->id;
            //     $transaction_id = time();
            //     if (Transaction::where('id', $transaction_id)->exists()) {
            //         $transaction->id = time();
            //     } else {
            //         $transaction->id =  $transaction_id += 1;
            //     }

            //     // hitung total sesi untuk menenukan harga
            //     $totalAmount = 0;
            //     foreach ($request->reservation as $courtId => $reservation) {
            //         foreach ($reservation as $index => $rentalSessionId) {
            //             $price =  RentalSession::where('id', $rentalSessionId)->select('price')->first()['price'];
            //             $sandBoxPrice = 0;
            //             // harga sementara selama pengembangan website
            //             if ($price == 40000) {
            //                 $sandBoxPrice = 1;
            //             } else if ($price == 50000) {
            //                 $sandBoxPrice = 1;
            //             }
            //             $totalAmount += $sandBoxPrice;
            //         }
            //     }
            //     //Count sum reservation
            //     $amountReservation = count($request->reservation);

            //     $transaction->total_amount = $totalAmount;
            //     //payment gateway here
            //     \Midtrans\Config::$serverKey = config('midtrans.server_key');
            //     // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
            //     // \Midtrans\Config::$isProduction = false;
            //     \Midtrans\Config::$isProduction = true;
            //     // Set sanitization on (default)
            //     \Midtrans\Config::$isSanitized = true;
            //     // Set 3DS transaction for credit card to true
            //     \Midtrans\Config::$is3ds = true;
            //     $orderDetails = array(
            //         'transaction_details' => array(
            //             'order_id' => $transaction_id,
            //             'gross_amount' => $transaction->total_amount,
            //         ),
            //         'customer_details' => array(
            //             'first_name' => Auth::user()->name,
            //             'last_name' => '',
            //             'phone' => Auth::user()->handphone_number,
            //         ),
            //         'item_details' => array(
            //             array(
            //                 'id' => $transaction_id,
            //                 'price' => $transaction->total_amount,
            //                 'quantity' => $amountReservation,
            //                 'name' => 'Sesi Lapangan',
            //             ),
            //         ),
            //     );
            //     $order = $request;
            //     $snapToken = \Midtrans\Snap::getSnapToken($orderDetails);
            //     $transaction->snapToken = $snapToken;
            //     $transaction->save();
            //     // transaction id blm ngurut
            //     foreach ($request->reservation as $courtId => $reservation) {
            //         foreach ($reservation as $index => $rentalSessionId) {
            //             $reservation = new Reservation;
            //             $reservation->user_id = Auth::user()->id;
            //             $reservation->rental_session_id = $rentalSessionId;
            //             $reservation->court_id = $courtId;
            //             $reservation->date = $request->date;
            //             $reservation->note = $request->note;
            //             $reservation->save();
            //             $reservation->transactions()->attach($transaction_id);
            //         }
            //     }
            //     $order['reservation'] = $request->reservation;
            //     foreach ($order['reservation'] as $courtId => $sesiId) {
            //         foreach ($sesiId as $key => $value) {
            //             $reservationDetail['court'][$courtId]['sesi'][$value] = [
            //                 'time' => RentalSession::where('id', $value)->select('rental_session_time')->first()['rental_session_time'],
            //                 'price' => RentalSession::where('id', $value)->select('price')->first()['price']
            //             ];
            //             $reservationDetail['court'][$courtId]['court_name'] = Court::find($courtId)['court_name'];
            //         }
            //     }

            //     // $transaction->snapToken = $snapToken;
            //     // $transaction->save();
            //     $rentalSession = RentalSession::all();
            //     // dd($order);
            //     return view('customer.checkout', compact('rentalSession', 'order', 'transaction', 'snapToken', 'reservationDetail', 'user'));
            // }





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
                        $sandBoxPrice = 1;
                    } else if ($price == 50000) {
                        $sandBoxPrice = 1;
                    }
                    $totalAmount += $sandBoxPrice;
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
                    'gross_amount' => $transaction->total_amount,
                ),
                'customer_details' => array(
                    'first_name' => Auth::user()->name,
                    'last_name' => '',
                    'phone' => Auth::user()->handphone_number,
                ),
                'item_details' => array(
                    array(
                        'id' => $transaction_id,
                        'price' => $transaction->total_amount,
                        'quantity' => $amountReservation,
                        'name' => 'Sesi Lapangan',
                    ),
                ),
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

            // $transaction->snapToken = $snapToken;
            // $transaction->save();
            $rentalSession = RentalSession::all();
            // dd($order);
            return view('customer.checkout', compact('rentalSession', 'order', 'transaction', 'snapToken', 'reservationDetail', 'user'));
            // return redirect('/checkout');
            // return redirect('/checkout-payment')->with(['snapToken' => $snapToken]);
            // return redirect('/checkout')->with([
            //     'rentalSession' => $rentalSession,
            //     'order' => $order,
            //     'transaction' => $transaction,
            //     'snapToken' => $snapToken,
            //     'reservationDetail' => $reservationDetail,
            //     'user' => $user
            //     ]);
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
    //
    //
    //
    //
    public function recurring(Request $request)
    {
        $status = 'recurring';
        return response()->json($status);
    }
    public function pay_account_notification(Request $request)
    {
        $status = 'pay_account_notification';
        return response()->json($status);
    }
    public function finish(Request $request)
    {
        $status = 'finish';
        return response()->json($status);
    }
    public function unfinish(Request $request)
    {
        $status = 'unfinish';
        return response()->json($status);
    }

    public function error(Request $request, $id)
    {
        echo $id;
    }

    //
    //
    //
    //
    //
    //

    public function create()
    {
        $court = Court::all();
        $rentalSession = RentalSession::all();
        return view('admin.reservation.create', compact('court'));
    }



    public function edit($id)
    {
        $reservation = Reservation::find($id);
        return view('admin.reservation.edit', ['reservation' => $reservation]);
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
        $reservation = Reservation::findOrFail($id);
        if (!$reservation || $reservation->user_id !== Auth::id()) {
            return redirect()->route('reservations.index')->with('error', 'Reservasi tidak ditemukan atau Anda tidak memiliki izin.');
        }

        $reservation->delete();

        return redirect()->route('reservations.index')->with('success', 'Reservasi berhasil dihapus.');

        // $court = Court::findOrFail($id);
        // $court->delete();


        // return redirect()->route('courts.index')
        // ->with('success', 'Court deleted successfully');


    }
}
