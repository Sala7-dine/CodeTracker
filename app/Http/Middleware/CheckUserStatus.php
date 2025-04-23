<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Vérifier si l'utilisateur est connecté et non actif
        if (Auth::check() && !Auth::user()->is_active) {
            // Déconnecter l'utilisateur
            Auth::logout();
            
            // Invalider la session et regénérer le token CSRF
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            // Rediriger vers la page de connexion avec un message d'erreur
            return redirect()->route('login')->with('error', 
                'Votre compte a été désactivé par l\'administrateur. Pour plus d\'informations, veuillez nous contacter.');
        }

        return $next($request);
    }
}