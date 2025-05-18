<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlockUserRole
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role?->name === 'User') {
            Auth::logout(); // Logout user
            return redirect()->route('user.login')->with('error', 'Akses ditolak. Hanya untuk Admin.');
        }

        return $next($request); // lanjut jika bukan role user
    }
}
