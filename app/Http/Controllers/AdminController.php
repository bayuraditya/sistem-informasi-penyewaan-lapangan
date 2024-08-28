<?php

namespace App\Http\Controllers;

date_default_timezone_set('Asia/Shanghai');

use DateTime;
use App\Models\Court;
use App\Models\RentalSession;
use App\Models\Reservation;
use App\Models\ReservationTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;


class AdminController extends Controller
{

    public function index()
    {
        $now = new DateTime();
        $today = $now->format('Y-m-d');
        $court = Court::orderBy('id', 'asc')->first();
        $user = Auth::user();
        
        $totalUser = User::count();
        $totalSettlementTransaction = Transaction::where('payment_status','settlement')->count();
        $totalReservation = Reservation::whereHas('transactions', function ($query) {
            $query->where('payment_status', ['settlement', 'capture']);
            })->count();
        $totalProfit = Transaction::whereIn('payment_status', ['settlement', 'capture'])->sum('total_amount');
        $transactions = Transaction::with(['user', 'reservations.court', 'reservations.rentalSession'])
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();
        return view('admin.admin', compact('today', 'court', 'user', 'totalUser', 'totalSettlementTransaction', 'totalReservation', 'totalProfit','transactions'));
    }

    public function reservation(Request $request)
    {
        $this->updatePendingPayment();
        $user = Auth::user();
        $reservations = Reservation::with('court', 'user', 'rentalSession', 'transactions')
        ->join('courts', 'reservations.court_id', '=', 'courts.id')
        ->join('users', 'reservations.user_id', '=', 'users.id')
        ->join('rental_sessions', 'reservations.rental_session_id', '=', 'rental_sessions.id')
        ->join('reservation_transaction', 'reservations.id', '=', 'reservation_transaction.reservation_id')
        ->join('transactions', 'reservation_transaction.transaction_id', '=', 'transactions.id')
        ->where(function($query) {
            $query->where('transactions.payment_status', 'settlement')
                ->orWhere('transactions.payment_status', 'capture');
        })
        ->where('reservations.date', $request->date)
        ->select(
            'reservations.id as reservation_id', 
            'reservations.*', 
            'courts.*', 
            'users.*', 
            'rental_sessions.*', 
            'transactions.*'
        )
        ->get();
        $date = $request->date;
        $now = new DateTime();
        $today = $now->format('Y-m-d');
        $court = Court::orderBy('id', 'asc')->first();
        $allCourt = Court::all();
        $rentalSession = RentalSession::all();
        return view('admin.reservation.index', compact('today', 'court', 'allCourt', 'reservations', 'rentalSession', 'date', 'user'));
    }

    public function profit(Request $request)
    {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        if ($request->startDate == null && $request->endDate == null) {
            $filteredProfit = 0;
            $filteredTotalBookedSession = 0;
        } else {
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
        $user = Auth::user();

        return view('admin.profit', compact('totalProfit', 'startDate', 'endDate', 'filteredProfit', 'filteredTotalBookedSession', 'user'));
    }
    public function transaction()
    {
        $this->updatePendingPayment();

        $now = new DateTime();
        $today = $now->format('Y-m-d');
        $transactions = Transaction::with(['user', 'reservations.court', 'reservations.rentalSession'])
            ->orderBy('created_at', 'desc')
            ->get();
        $user = Auth::user();
        // dd($transactions[68]['reservations'][0]);
        return view('admin.transaction.index', compact('transactions', 'user', 'today'));
    }

    public function deleteReservationTransaction($id){
        
        $transaction = Transaction::findOrFail($id);
        $transactionId = Transaction::findOrFail($id)->id;
        $reservationTransaction = ReservationTransaction::where('transaction_id',$transactionId)->get();
        foreach($reservationTransaction as $rt){
            $reservation = Reservation::where('id',$rt->id)->first();
            $reservation->transactions()->detach($transactionId);
            $reservation->delete();
        }        
        $transaction->delete();
        return redirect('/admin/transaction')
        ->with('success', 'Transaction deleted successfully');
    }

    public function allUser()
    {
        $this->updatePendingPayment();

        $user = Auth::user();
        $allUser = User::where('role', 'customer')
            ->with(['transactions.reservations'])
            ->get();
        return view('admin.user.index', compact('user', 'allUser'));
    }

    public function userDetail($id)
    {
        $this->updatePendingPayment();

        $userDetail = User::findOrFail($id);
        $transactions = Transaction::with(['user', 'reservations.court', 'reservations.rentalSession'])
            ->orderBy('transaction_time', 'desc')
            ->where('user_id', $userDetail->id)
            ->get();
        $reservations = Reservation::with('court', 'user', 'rentalSession', 'transactions')
            ->join('courts', 'reservations.court_id', '=', 'courts.id')
            ->join('users', 'reservations.user_id', '=', 'users.id')
            ->join('rental_sessions', 'reservations.rental_session_id', '=', 'rental_sessions.id')
            ->join('reservation_transaction', 'reservations.id', '=', 'reservation_transaction.reservation_id')
            ->join('transactions', 'reservation_transaction.transaction_id', '=', 'transactions.id')
            ->select('courts.*', 'users.*', 'rental_sessions.*', 'transactions.*', 'reservations.*')
            ->selectRaw('reservations.id AS reservation_id, courts.id AS court_id, users.id AS user_id, rental_sessions.id AS rental_session_id, transactions.id AS transaction_id')
            ->where('users.id', $userDetail->id)
            ->get();
        $user = Auth::user();
        return view('admin.user.detail', compact('transactions', 'userDetail', 'user'));
    }
    

    public function selectDate(){
        // date_default_timezone_set('Asia/Shanghai');
        $todayDateTime = new DateTime();
        $todayDate = $todayDateTime->format('Y-m-d');
        $user = Auth::user();
        return view('admin.reservation.home', compact('user', 'todayDate'));
    }

    public function availableCourts(Request $request){
        // dd($request->member_category);
        $this->updatePendingPayment();
        date_default_timezone_set('Asia/Shanghai');
        $date = $request->input('date');
        $court = $request->input('court');
        $memberCategory = $request->memberCategory;
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
            return redirect('/admin/reservation/select-date');
        } else {
            return view('admin.reservation.available-courts', compact('memberCategory','unavailableReservations', 'reservations', 'date', 'court', 'allCourt', 'rentalSessions', 'user'));
        }
    }

    public function book(Request $request){
        $memberCategory=$request->memberCategory;
        $rentalSession = RentalSession::all();
        $allCourt = Court::all();
        $order = array();
        $order['user_id'] = Auth::user()->id;
        $order['date'] = $request->date;
        $order['reservation'] = $request->reservation;
        if ($request->has('reservation')) {
            foreach ($order['reservation'] as $courtId => $sesiId) {
                        foreach ($sesiId as $key => $value) {
                            if($memberCategory == 'member'){
                                $reservation['court'][$courtId]['sesi'][$value] = [
                                    'time' => RentalSession::where('id', $value)->select('rental_session_time')->first()['rental_session_time'],
                                    'price' => RentalSession::where('id', $value)->select('price')->first()['price']-=5000
                                ];
                                $reservation['court'][$courtId]['court_name'] = Court::find($courtId)['court_name'];
                            }else{
                                $reservation['court'][$courtId]['sesi'][$value] = [
                                    'time' => RentalSession::where('id', $value)->select('rental_session_time')->first()['rental_session_time'],
                                    'price' => RentalSession::where('id', $value)->select('price')->first()['price']
                                ];
                                $reservation['court'][$courtId]['court_name'] = Court::find($courtId)['court_name'];
                            }
                           
                        }
                    }
        } else {
            return redirect('/admin/reservation/available-courts?date='.$request->date);
        }
        $user = Auth::user();
        return view('admin.reservation.booking-detail', compact('memberCategory','reservation', 'order', 'rentalSession', 'allCourt', 'user'));
    }
    
    public function store(Request $request){
        // dd($request);
        $this->updatePendingPayment();
        $user = Auth::user();
        $reservations = Reservation::with('court', 'user', 'rentalSession', 'transactions')
        ->join('courts', 'reservations.court_id', '=', 'courts.id')
        ->join('users', 'reservations.user_id', '=', 'users.id')
        ->join('rental_sessions', 'reservations.rental_session_id', '=', 'rental_sessions.id')
        ->join('reservation_transaction', 'reservations.id', '=', 'reservation_transaction.reservation_id')
        ->join('transactions', 'reservation_transaction.transaction_id', '=', 'transactions.id')
        ->where(function($query) {
            $query->where('transactions.payment_status', 'settlement')
                ->orWhere('transactions.payment_status', 'capture');
        })
        ->where('reservations.date', $request->date)
        ->select(
            'reservations.id as reservation_id', 
            'reservations.*', 
            'courts.*', 
            'users.*', 
            'rental_sessions.*', 
            'transactions.*'
        )
        ->get();
        $date = $request->date;
        $now = new DateTime();
        $today = $now->format('Y-m-d');
        $court = Court::orderBy('id', 'asc')->first();
        $allCourt = Court::all();
        $rentalSession = RentalSession::all();
        $user = Auth::user();
        date_default_timezone_set('Asia/Shanghai');
            $date = $request->input('date');
            $todayDateTime = new DateTime();
            $today = $todayDateTime->format('Y-m-d');
            $now = $todayDateTime->format('Y-m-d H:i:s');

            $transaction = new Transaction;
            $transaction->user_id = Auth::user()->id;
            $transaction_id = time();
            
            $transaction->payment_status = 'settlement';
            $transaction->payment_method = 'manual';
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
                    $price-=5000;
                    $sandBoxPrice = 0;
                    // harga sementara selama pengembangan website
                    if ($price == 40000) {
                        $sandBoxPrice = 1;
                    } else if ($price == 50000) {
                        $sandBoxPrice = 1;
                    }
                    // $totalAmount += $sandBoxPrice;
                    $totalAmount += $price;
                }
            }
            //Count sum reservation
            $amountReservation = count($request->reservation);

            $transaction->total_amount = $totalAmount;
            //payment gateway here
            // \Midtrans\Config::$serverKey = config('midtrans.server_key');
            // // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
            // // \Midtrans\Config::$isProduction = false;
            // \Midtrans\Config::$isProduction = true;
            // // Set sanitization on (default)
            // \Midtrans\Config::$isSanitized = true;
            // // Set 3DS transaction for credit card to true
            // \Midtrans\Config::$is3ds = true;
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
            // $snapToken = \Midtrans\Snap::getSnapToken($orderDetails);
            // $transaction->snapToken = $snapToken;
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
            // return view('admin.reservation.index', compact('date','today','court','allCourt','reservations','rentalSession', 'order', 'transaction', 'reservationDetail', 'user'));
            return redirect('/admin/reservation?date='.$reservation->date);
    }

    public function invoice($id){
        $transaction = Transaction::find($id);
        $user = Auth::user();
        return view('admin.reservation.invoice', compact('user', 'transaction'));
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