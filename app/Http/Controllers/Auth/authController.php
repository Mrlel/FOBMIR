<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class authController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validation des champs
        $request->validate([
            'telephone' => 'required|string',
            'password'  => 'required|string',
        ]);
    
        // Chercher l'utilisateur via le numéro de téléphone
        $user = User::where('telephone', $request->telephone)->first();
    
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'telephone' => 'Numéro de téléphone ou mot de passe incorrect.',
            ]);
        }
    
        // Connexion de l’utilisateur
        Auth::login($user);
        // Regénérer la session pour sécurité
        $request->session()->regenerate();
    
        // Redirection après connexion
        return redirect()->intended($this->dashboardRouteFor($user))
            ->with('success', 'Connexion réussie ! Bienvenue');
    }

    /**
     * Détermine la route de tableau de bord adaptée au rôle de l'utilisateur.
     * Les rôles admin/superadmin ne sont pas redirigés vers point_focal.dashboard
     * (qui n'affiche que les individus du point focal connecté), et inversement
     * point_focal n'a pas accès à admin.dashboard (protégé par le middleware "admin").
     */
    private function dashboardRouteFor(User $user): string
    {
        return match ($user->role) {
            'admin', 'superadmin' => route('admin.dashboard'),
            'point_focal' => route('point_focal.dashboard'),
            default => route('userDashboard'),
        };
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.form');
    }

    public function showChangePasswordForm()
    {
        return view('Auth.change-password');
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required|string',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Le mot de passe actuel est incorrect.',
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password),
            'must_change_password' => false,
        ]);

        return redirect()->to($this->dashboardRouteFor($user))
            ->with('success', 'Mot de passe modifié avec succès.');
    }

}
