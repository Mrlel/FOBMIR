<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        return redirect()->intended(
            $user->role === 'admin' || $user->role === 'point_focal' || $user->role === 'superadmin'
                ? route('admin.dashboard')
                : route('userDashboard')
        )->with('success', 'Connexion réussie ! Bienvenue');
    }
    

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.form');
    }

}
