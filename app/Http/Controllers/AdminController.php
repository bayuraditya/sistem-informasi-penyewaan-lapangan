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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;


class AdminController extends Controller
{
    //     public function updatePendingPayment(){
    //         //reset pending ke exp yg tidak payment
    //         $now = new DateTime();
    //         $nowDate = $now->format('Y-m-d H:i:s');
    //         $transactionCreated = Transaction::Where('transactions.payment_status', '=', 'pending')->pluck('created_at');
    //         foreach($transactionCreated as $tr){
    //             // $transaction = Transaction::where('created_at', $tr)->get();
    //             $transactionId = Transaction::where('created_at', $tr)->value('id');
    //             $expireTime = date('Y-m-d H:i:s', strtotime($tr . '+15 minutes'));
    //             // echo $now . '<br>'. $expireTime . '<br>';
    //             if($expireTime < $nowDate){
    //                 $transactionData = [
    //                     'payment_status' => 'expire'
    //                 ];
    //                 $transaction = Transaction::find($transactionId);;
    //                 $transaction->update($transactionData);
    //             }
    //         }
    //    }

    public function index()
    {
        // compact today dan court a
        $now = new DateTime();
        $today = $now->format('Y-m-d');
        $court = Court::orderBy('id', 'asc')->first();
        $user = Auth::user();
        return view('admin.admin', compact('today', 'court', 'user'));
    }

    public function reservation(Request $request)
    {
        $this->updatePendingPayment();

        // dd($request);
        $user = Auth::user();
        $reservations = Reservation::with('court', 'user', 'rentalSession', 'transactions')
            ->join('courts', 'reservations.court_id', '=', 'courts.id')
            ->join('users', 'reservations.user_id', '=', 'users.id')
            ->join('rental_sessions', 'reservations.rental_session_id', '=', 'rental_sessions.id')
            ->join('reservation_transaction', 'reservations.id', '=', 'reservation_transaction.reservation_id')
            ->join('transactions', 'reservation_transaction.transaction_id', '=', 'transactions.id')
            ->where('transactions.payment_status', 'settlement')
            ->where('reservations.date', $request->date)
            // ->where('reservations.court_id', $request->court_id)
            ->orWhere('transactions.payment_status', 'capture')
            ->select('reservations.*', 'courts.*', 'users.*', 'rental_sessions.*', 'transactions.*')
            ->get();
        $date = $request->date;
        $now = new DateTime();
        $today = $now->format('Y-m-d');
        $court = Court::orderBy('id', 'asc')->first();
        $allCourt = Court::all();
        $rentalSession = RentalSession::all();
        return view('admin.reservation.index', compact('today', 'court', 'allCourt', 'reservations', 'rentalSession', 'date', 'user'));
    }

    public function createReservation()
    {
        $user = Auth::user();

        return view('admin.reservation.create', compact('court', 'user'));
    }


    public function callback(Request $request)
    {
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
            ->orderBy('transaction_time', 'desc')
            ->get();
        $user = Auth::user();
        return view('admin.transaction.index', compact('transactions', 'user', 'today'));
    }

    public function allUser()
    {
        $this->updatePendingPayment();

        // $allUser = User::where('role', 'customer')->get();
        $user = Auth::user();
        $allUser = User::where('role', 'customer')
            ->with(['transactions.reservations'])
            ->get();
        // dd($allUser[1]['transactions'][0]['reservations'][0]);
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
            // ->where('transactions.payment_status', 'settlement')
            // ->where('reservations.date', $request->date)
            // ->where('reservations.court_id', $request->court_id)
            // ->orWhere('transactions.payment_status', 'capture')
            ->select('courts.*', 'users.*', 'rental_sessions.*', 'transactions.*', 'reservations.*')
            ->selectRaw('reservations.id AS reservation_id, courts.id AS court_id, users.id AS user_id, rental_sessions.id AS rental_session_id, transactions.id AS transaction_id')
            ->where('users.id', $userDetail->id)
            ->get();
        $user = Auth::user();
        return view('admin.user.detail', compact('transactions', 'userDetail', 'user'));
    }
    public function selectDate()
    {
        // date_default_timezone_set('Asia/Shanghai');
        $todayDateTime = new DateTime();
        $todayDate = $todayDateTime->format('Y-m-d');
        $user = Auth::user();
        return view('admin.reservation.home', compact('user', 'todayDate'));
    }
    public function availableCourts(Request $request)
    {
        //reset pending ke exp yg tidak payment
        $now = new DateTime();
        $nowDate = $now->format('Y-m-d H:i:s');
        $transactionCreated = Transaction::Where('transactions.payment_status', '=', 'pending')->pluck('created_at');
        foreach ($transactionCreated as $tr) {
            // $transaction = Transaction::where('created_at', $tr)->get();
            $transactionId = Transaction::where('created_at', $tr)->value('id');
            $expireTime = date('Y-m-d H:i:s', strtotime($tr . '+15 minutes'));
            // echo $now . '<br>'. $expireTime . '<br>';
            if ($expireTime < $nowDate) {
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
            return view('admin.reservation.available-courts', compact('unavailableReservations', 'reservations', 'date', 'court', 'allCourt', 'rentalSessions', 'user'));
        }
    }

    public function book(Request $request)
    {
        $rentalSession = RentalSession::all();
        $allCourt = Court::all();
        $order = array();
        $order['user_id'] = Auth::user()->id;
        $order['date'] = $request->date;
        $order['reservation'] = $request->reservation;
        foreach ($order['reservation'] as $courtId => $sesiId) {
            foreach ($sesiId as $key => $value) {
                $reservation['court'][$courtId]['sesi'][$value] = [
                    'time' => RentalSession::where('id', $value)->select('rental_session_time')->first()['rental_session_time'],
                    'price' => RentalSession::where('id', $value)->select('price')->first()['price']
                ];
                $reservation['court'][$courtId]['court_name'] = Court::find($courtId)['court_name'];
            }
        }
        $user = Auth::user();
        return view('admin.reservation.booking-detail', compact('reservation', 'order', 'rentalSession', 'allCourt', 'user'));
    }

    public function store(Request $request)
    {
        date_default_timezone_set('Asia/Shanghai');
        $date = $request->input('date');
        $todayDateTime = new DateTime();
        $today = $todayDateTime->format('Y-m-d');
        $now = $todayDateTime->format('Y-m-d H:i:s');

        $transaction = new Transaction;
        $transaction->user_id = Auth::user()->id;
        $transaction_id =  $transaction->user_id . strtotime($now);
        $transaction->id = intval($transaction_id);
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
                    $sandBoxPrice = 2;
                }
                $totalAmount += $sandBoxPrice;
            }
        }
        $transaction->total_amount = $totalAmount;
        //payment gateway here
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        // \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = env('MIDTRANS_IS_SANITIZED');
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = env('MIDTRANS_IS_3DS');
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
        $order = $request;
        $snapToken = \Midtrans\Snap::getSnapToken($orderDetails);
        dd($snapToken);
        $transaction->snapToken = $snapToken;
        $transaction->save();
        // ubah transaction id jadi kode unik ?
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
        $user = Auth::user();
        return view('admin.reservation.checkout', compact('rentalSession', 'order', 'transaction', 'snapToken', 'reservationDetail', 'user'));
    }
    public function invoice($id)
    {
        $transaction = Transaction::find($id);
        return view('admin.reservation.invoice', compact('transaction'));
    }


    public function edit()
    {
        $user = Auth::user();
        return view('admin.profile.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->handphone_number = $request->handphone_number;
        $user->save();
        if ($user->save()) {
            Session::flash('success', 'User updated successfully');
            return redirect()->route('admin.edit')->with('success', 'User updated successfully');
        } else {
            Session::flash('success', 'User updated successfully');
            return redirect()->route('admin.edit')->with('error', 'Failed to update user');
        }
        // return view('customer.profile.edit',compact('user'))->with('success', 'user updated successfully');
    }
    public function showChangePasswordForm()
    {
        $user = Auth::user();
        return view('admin.profile.change-password', compact('user'))->with('success', 'Profile updated successfully');
    }
    public function changePassword(Request $request, $id)
    {
        // Validasi input
        $validatedData = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|different:current_password|confirmed',
        ], [
            'new_password.different' => 'Kata sandi baru harus berbeda dengan kata sandi saat ini.',
            'new_password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        // Dapatkan pengguna yang sedang login
        $user = User::findOrFail($id);

        // Cek apakah password saat ini cocok dengan yang diinputkan
        if (!Hash::check($validatedData['current_password'], $user->password)) {
            // Kembali ke halaman sebelumnya dengan pesan error
            return redirect()->back()->with('error', 'Kata sandi saat ini salah.');
        }

        // Update password pengguna dengan password baru
        $user->password = Hash::make($validatedData['new_password']);
        $user->save();

        // Redirect dengan pesan sukses
        return redirect()->route('adminShowChangePasswordForm')->with('success', 'Kata sandi berhasil diubah.');
    }
}
