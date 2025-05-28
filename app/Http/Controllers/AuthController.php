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
        $request->validate(
            [
                'username' => 'required|string|max:255',
                'password' => 'required|string',
            ],

            // [
            //     'username.required' => 'Username harus diisi',
            //     'password.required' => 'Password harus diisi',
            //     'password.min' => 'Password minimal 3 karakter',
            // ]
        );

        $credentials = [
            'username' => $request->username,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {
            // Ambil user yang sedang login
            $user = Auth::user();

            // Cek apakah role-nya adalah 'user'
            if ($user->role?->name === 'User') {
                Auth::logout(); // logout langsung
                return redirect()->back()->with('warning', 'Akses tidak diperbolehkan untuk user biasa.');
            }

            // Jika bukan user biasa, lanjut ke dashboard
            return redirect()->route('dashboard')->with('login-sukses', 'Login successful');
        }

        // Jika gagal login
        return redirect()->back()->with('error', 'Invalid Username or Password');
    }


    public function userLogin(Request $request)
    {
        $request->validate(
            [
                'nik' => 'required|string',
            ],
            // [
            //     'nik.required' => 'ID Card harus diisi',
            // ]
        );

        $user = User::where('nik', $request->nik)->first();

        if ($user) {
            // Cek role
            if ($user->role?->name !== 'User') {
                return redirect()->back()->with('warning', 'Akses hanya diperbolehkan untuk user role.');
            }


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
