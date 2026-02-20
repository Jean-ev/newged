<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->status === 'bloque') {
                Auth::logout();
                return redirect()->route('login')
                    ->withErrors(['email' => 'Votre compte a été bloqué. Contactez l\'administrateur.']);
            }

            if ($user->status === 'en_attente') {
                Auth::logout();
                return redirect()->route('login')
                    ->withErrors(['email' => 'Votre compte est en attente de validation par un administrateur.']);
            }
        }

        return $next($request);
    }
}