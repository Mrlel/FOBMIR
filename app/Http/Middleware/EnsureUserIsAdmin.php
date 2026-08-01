<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Autorise uniquement les utilisateurs authentifiés avec le rôle admin ou superadmin.
     * A appliquer après le middleware "auth" sur les routes d'administration.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user || ! in_array($user->role, ['admin', 'superadmin'], true)) {
            abort(403, 'Accès réservé aux administrateurs.');
        }

        return $next($request);
    }
}
