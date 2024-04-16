<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // Lakukan pemeriksaan peran pengguna
        if (!$request->user()->hasRole($role)) {
            // Jika pengguna tidak memiliki peran yang sesuai, Anda dapat mengembalikan respons atau melempar pengecualian
            return redirect()->route('unauthorized'); // Ganti ini dengan route atau respons yang sesuai
        }

        return $next($request);
    }
}
