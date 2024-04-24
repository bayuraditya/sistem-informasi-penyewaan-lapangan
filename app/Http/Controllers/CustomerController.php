<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

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
    public function showChangePasswordForm(){
        $user = Auth::user();
        return view('customer.profile.change-password',compact('user'))->with('success', 'Profile updated successfully');
    }
    public function changePassword(Request $request, $id){

        // $request->validate([
        //     'current_password' => 'required',
        //     'new_password' => 'required|min:8|confirmed',
        // ]);

        // $user = Auth::user();
        $user = User::findOrFail($id);
        // cek input password lama dan password di database 
        if (!Hash::check($request->current_password, $user->password)) {
            // return redirect()->back()->with('error', 'Password saat ini tidak cocok.');
            echo 'salah';
        }else{
            $user->password = Hash::make($request->new_password);
            $user->save();
            echo 'benar';
        }

        
        // return redirect()->route('home')->with('success', 'Password berhasil diubah.');
    
    
       
    }
}
