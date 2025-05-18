<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role?->name=== 'User') {
            return $next($request);
        }

        Auth::logout(); // Logout jika bukan user
        return redirect()->route('user.login')->with('error', 'Akses ditolak: hanya untuk User.');
    }
}
