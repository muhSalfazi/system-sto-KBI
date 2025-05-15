<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserSession;

class AuthController extends Controller
{
    //
    public function showAdmin()
    {
        return view('auth.admin');  // Tampilan login
    }
    public function showUser()
    {
        return view('auth.user-login');  // Tampilan user login
    }

    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ]);

        // Cek kredensial login
        $credentials = [
            'username' => $request->username,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {
            // Otentikasi berhasil, arahkan ke halaman dashboard
            return redirect()->route('dashboard')->with('login-sukses', 'Login successful');
        }


        // Jika gagal login, kirim error dan kembali ke halaman login
        return redirect()->back()->with('error', 'Invalid Username or Password');
    }


    public function userLogin(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|min:2',
        ]);

        $user = User::where('nik', $request->nik)->first();

        if ($user) {
            Auth::login($user); // login manual tanpa password
            return redirect()->route('dailyreport.index')->with('success', 'Login berhasil');
        }

        return redirect()->back()->with('error', 'ID Card tidak ditemukan');
    }

    public function logout()
    {
        Auth::logout();  // Keluar dari akun
        return redirect()->route('admin.login');
    }
    public function logoutUser()
    {
        Auth::logout();  // Keluar dari akun
        return redirect()->route('admin.login');
    }
}
