<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsUser
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan user sudah login dan memiliki role user (role_id == '2')
        if (Auth::check() && Auth::user()->role_id == '2') {
            return $next($request);
        }

        // Jika dia admin, arahkan ke dashboard admin
        if (Auth::check() && Auth::user()->role_id == '1') {
            return redirect('/admin/dashboard')->with('error', 'Anda bukan user biasa.');
        }

        // Jika bukan terautentikasi atau bukan user, tolak akses
        return redirect('/')->with('error', 'Akses ditolak.');
    }
}
