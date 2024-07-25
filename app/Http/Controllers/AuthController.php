<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth/login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/admin');
            } elseif (Auth::user()->role === 'customer') {
                return redirect()->intended('/');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    // Method untuk melakukan logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Method untuk menangani proses registrasi
    public function register(Request $request)
    {
        // Validasi data yang diterima dari form registrasi
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'handphone_number' => 'required|string|max:20|unique:users',

        ]);

        // Membuat user baru
        User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'handphone_number' => $validatedData['handphone_number']
        ]);

        // Redirect ke halaman tertentu setelah registrasi berhasil
        return redirect('/login')->with('success', 'Registration successful. Please log in.');
    }
}
