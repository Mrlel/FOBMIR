<?php

namespace App\Http\Controllers;

use App\Models\IndividuIndependant;
use App\Models\Ville;
use App\Models\Pays;
use App\Models\District;
use App\Models\Region;
use App\Models\Departement;
use App\Models\SousPrefecture;
use App\Models\Commune;
use App\Mail\VerificationEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AutoEnregistrementController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auto-enregistrement.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:individus_independants',
            'password' => ['required', 'confirmed', Password::defaults()],
            'telephone' => 'nullable|string|max:20',
            'date_naissance' => 'nullable|date|before:today',
            'sexe' => 'nullable|in:M,F',
            'profession' => 'nullable|string|max:255',
            'adresse_complete' => 'nullable|string',

            // Géolocalisation GOP
            'latitude' => 'numeric|between:-90,90',
            'longitude' => 'numeric|between:-180,180',
        ]);

        // Créer l'individu indépendant
        $individu = IndividuIndependant::create([
            'prenom' => $request->prenom,
            'nom' => $request->nom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telephone' => $request->telephone,
            'date_naissance' => $request->date_naissance,
            'sexe' => $request->sexe,
            'profession' => $request->profession,
            'adresse_complete' => $request->adresse_complete,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'verification_token' => Str::random(60),
        ]);

        // Créer automatiquement la structure de documents
        $individu->creerStructureDocuments();
        // Envoyer l'email de vérification
        $this->envoyerEmailVerification($individu);

        // Connecter automatiquement l'utilisateur
        Auth::guard('individu')->login($individu);

        return redirect()->route('individu.dashboard')
            ->with('success', 'Votre compte a été créé avec succès ! Un email de vérification vous a été envoyé.');
    }

    public function showLoginForm()
    {
        return view('auto-enregistrement.login');
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::guard('individu')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $individu = Auth::guard('individu')->user();
            $individu->update([
                'derniere_connexion' => now(),
            ]);

            return redirect()->route('individu.dashboard');
        }

        return back()
            ->withErrors([
                'email' => 'Les informations de connexion ne correspondent pas à nos enregistrements.',
            ])
            ->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('individu')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auto-enregistrement.login');
    }

    public function dashboard()
    {
        $individu = Auth::guard('individu')->user();

        // Sécurité supplémentaire
        if (!$individu) {
            return redirect()->route('auto-enregistrement.login');
        }

        return view('individu.dashboard', compact(
            'individu',
        ));
    }
    public function verifyEmail($token)
    {
        $individu = IndividuIndependant::where('verification_token', $token)->first();

        if (!$individu) {
            return redirect()->route('auto-enregistrement.login')
                ->with('error', 'Token de vérification invalide.');
        }

        $individu->update([
            'email_verifie' => true,
            'email_verified_at' => now(),
            'verification_token' => null
        ]);

        $individu->marquerProfilComplet();

        return redirect()->route('individu.dashboard')
            ->with('success', 'Votre email a été vérifié avec succès !');
    }

    public function resendVerificationEmail()
    {
        $individu = Auth::guard('individu')->user();

        if ($individu->email_verifie) {
            return back()->with('info', 'Votre email est déjà vérifié.');
        }

        if (!$individu->verification_token) {
            $individu->update(['verification_token' => Str::random(60)]);
        }

        $this->envoyerEmailVerification($individu);

        return back()->with('success', 'Email de vérification renvoyé !');
    }

    private function envoyerEmailVerification($individu)
    {
        try {
            Mail::to($individu->email)->send(new VerificationEmail($individu));
            \Log::info("Email de vérification envoyé à {$individu->email}");
        } catch (\Exception $e) {
            \Log::error("Erreur d'envoi d'email à {$individu->email}: " . $e->getMessage());
        }
    }
}