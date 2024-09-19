<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordMail;
use App\Models\Court;
use App\Models\RentalSession;
use App\Models\Reservation;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use DateInterval;
use function Ramsey\Uuid\v1;
use App\Mail\WelcomeMail;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Mail;

class CustomerController extends Controller
{
    public function edit(){
        $user = Auth::user();     
        return view('customer.profile.edit',compact('user'));
    }
    
    public function update(Request $request, $id){
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->handphone_number = $request->handphone_number;
        $user->save();
        if ($user->save()) {
            Session::flash('success', 'User updated successfully');
            return redirect()->route('Customer.edit')->with('success', 'User updated successfully');
        } else {
            Session::flash('success', 'User updated successfully');
            return redirect()->route('Customer.edit')->with('error', 'Failed to update user');
        }
        // return view('customer.profile.edit',compact('user'))->with('success', 'user updated successfully');
    }
    public function forgotPassword(){
        return view('auth.forgot-password');
    }
    public function resetPassword(Request $request,$id){
        //user masukan email, ok
        //kirim ke email user link changepassword id
        // cari id by email
        //ke changepassowrd/id
        
        return view('auth.reset-password',compact('id'));
    }
    public function sendEmail(Request $request)
    {
        $email = $request->email;
        $id = User::where('email', $email)->first()->id;
        // dd($id);
        Mail::to($email)->send(new ResetPasswordMail($id));

        return view('auth.email-send');
    }
    public function showChangePasswordForm(){
        $user = Auth::user();
        return view('customer.profile.change-password',compact('user'))->with('success', 'Profile updated successfully');
    }
    public function newPassword(Request $request,$id)
    {
        // Validasi input
        $validatedData = $request->validate([
            'new_password' => 'required|min:8|different:current_password|confirmed',
        ], [
            'new_password.different' => 'Kata sandi baru harus berbeda dengan kata sandi saat ini.',
            'new_password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);
        // Dapatkan pengguna yang sedang login
        $user = User::findOrFail($id);
        // Cek apakah password saat ini cocok dengan yang diinputkan
      
        // Update password pengguna dengan password baru
        $user->password = Hash::make($validatedData['new_password']);
        $user->save();
    
        // Redirect dengan pesan sukses
        return redirect()->route('login')->with('success', 'Kata sandi berhasil diubah.');
    }
    public function changePassword(Request $request,$id)
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
        return redirect()->route('showChangePasswordForm')->with('success', 'Kata sandi berhasil diubah.');
    }

    public function reservationHistory(){
        $this->updatePendingPayment();
        $user = Auth::user();
        $reservations = Reservation::with('court', 'user', 'rentalSession', 'transactions')
        ->join('courts', 'reservations.court_id', '=', 'courts.id')
        ->join('users', 'reservations.user_id', '=', 'users.id')
        ->join('rental_sessions', 'reservations.rental_session_id', '=', 'rental_sessions.id')
        ->join('reservation_transaction', 'reservations.id', '=', 'reservation_transaction.reservation_id')
        ->join('transactions', 'reservation_transaction.transaction_id', '=', 'transactions.id')
        ->select( 'courts.*', 'users.*', 'rental_sessions.*', 'transactions.*','reservations.*')
        ->selectRaw('reservations.id AS reservation_id, courts.id AS court_id, users.id AS user_id, rental_sessions.id AS rental_session_id, transactions.id AS transaction_id')    
        ->where('users.id', $user->id)
        ->orderBy('date', 'desc') 
        ->get();
   
        return view('customer.profile.reservation-history',compact('reservations','user'));
    }
    
    public function transactionHistory(){
        $this->updatePendingPayment();
        $user = Auth::user();
        $transactions = Transaction::with(['user','reservations'])
        ->orderBy('created_at', 'desc') 
        ->where('user_id', $user->id)
        ->get();
        $todayDateTime = new DateTime();
        $now = $todayDateTime->format('Y-m-d H:i:s');
        // dd($transactions[0]->reservations[1]);
        return view('customer.profile.transaction-history',compact('transactions','user','now'));
    }
    public function reschedule(Request $request){
        $this->updatePendingPayment();

        // code update tanggal reservasi
        // foreach reservasi
        // reservation foreach
        // find id
        // res date = request date
        // save

        // $court = Court::findOrFail($id);
        // $court->court_name = $request->court_name;
        // $court->description = $request->description;
        // $court->save();
        // dd($request);
        $user = Auth::user();

        $today = new DateTime();
        $tomorrow = $today->modify('+1 day');
        $rescheduleDate = $request->rescheduleDate;
        if($rescheduleDate < $tomorrow){
            $transactions = Transaction::with(['user','reservations'])
            ->orderBy('created_at', 'desc') 
            ->where('user_id', $user->id)
            ->get();
            return redirect()->route('transactionHistory')
            ->with('transactions',$transactions)
            ->with('user',$user);
        }
        foreach($request->reservations as $r){
            $reservation = Reservation::findOrFail($r);
            $reservation->date = $rescheduleDate;
            $reservation->save();   
        }

        $transactions = Transaction::with(['user','reservations'])
        ->orderBy('created_at', 'desc') 
        ->where('user_id', $user->id)
        ->get();
        $todayDateTime = new DateTime();
        $now = $todayDateTime->format('Y-m-d H:i:s');
        return redirect()->route('transactionHistory')
                        ->with('transactions',$transactions)
                        ->with('user',$user);
        // return view('customer.profile.transaction-history',compact('transactions','user'));
    }
    public function cancel(Request $request,$id){
        $user = Auth::user();
        $transaction = Transaction::findOrFail($id);
        $transaction->payment_status = 'expire';
        $transaction->save();
        $transactions = Transaction::with(['user','reservations'])
        ->orderBy('created_at', 'desc') 
        ->where('user_id', $user->id)
        ->get();
        return redirect()->route('transactionHistory')
                        ->with('success', 'Pesanan Dibatalkan')
                        ->with('transactions',$transactions)
                        ->with('user',$user);
    }
       
}
