<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    /**
     * Redirige tout utilisateur (guard "web") ayant must_change_password = true
     * vers le formulaire de changement de mot de passe, tant qu'il ne l'a pas modifié.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('web')->user();

        if (
            $user
            && $user->must_change_password
            && ! $request->routeIs('password.change.form', 'password.change.update', 'logout')
        ) {
            return redirect()->route('password.change.form')
                ->with('warning', 'Vous devez changer votre mot de passe avant de continuer.');
        }

        return $next($request);
    }
}
