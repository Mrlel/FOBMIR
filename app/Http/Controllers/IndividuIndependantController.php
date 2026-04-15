<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;


class IndividuIndependantController extends Controller
{
    public function showProfile()
    {
        $individu = Auth::guard('individu')->user();
        return view('individu.profile', compact('individu'));
    }

    public function updateProfile(Request $request)
    {
        $individu = Auth::guard('individu')->user();

        $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:individus_independants,email,' . $individu->id,
            'telephone' => 'nullable|string|max:20',
            'date_naissance' => 'nullable|date|before:today',
            'sexe' => 'nullable|in:M,F',
            'profession' => 'nullable|string|max:255',
            'adresse_complete' => 'nullable|string',
            // Coordonnées GPS obligatoire
            'latitude' => 'numeric|between:-90,90',
            'longitude' => 'numeric|between:-180,180',

            // Changement de mot de passe optionnel
            'current_password' => 'nullable|required_with:password',
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        // Vérifier le mot de passe actuel si un nouveau est fourni
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $individu->password)) {
                return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
            }
        }

        // Vérifier si l'email a changé
        $emailChanged = $request->email !== $individu->email;

        // Mettre à jour les informations
        $updateData = $request->only([
            'prenom',
            'nom',
            'email',
            'telephone',
            'date_naissance',
            'sexe',
            'profession',
            'adresse_complete',
            'latitude',
            'longitude'
        ]);

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        // Si l'email a changé, marquer comme non vérifié
        if ($emailChanged) {
            $updateData['email_verifie'] = false;
            $updateData['email_verified_at'] = null;
            $updateData['verification_token'] = \Str::random(60);
        }

        $individu->update($updateData);

        // Marquer le profil comme complet
        $individu->marquerProfilComplet();

        $message = 'Profil mis à jour avec succès !';
        if ($emailChanged) {
            $message .= ' Un email de vérification a été envoyé à votre nouvelle adresse.';
            // TODO: Envoyer l'email de vérification
        }

        return back()->with('success', $message);
    }

    public function showPochette()
    {
        $individu = Auth::guard('individu')->user();
        $pochette = $individu->pochetteIndependant;

        if (!$pochette) {
            // Créer la structure si elle n'existe pas
            $pochette = $individu->creerStructureDocuments();
        }

        return view('individu.pochette', compact('individu', 'pochette'));
    }

    public function showDossier()
    {
        $individu = Auth::guard('individu')->user();
        $dossier = $individu->dossierIndependant;

        if (!$dossier) {
            // Créer la structure si elle n'existe pas
            $individu->creerStructureDocuments();
            $dossier = $individu->dossierIndependant;
        }

        $classeurs = $individu->classeurs()->active()->with('documents')->orderBy('ordre')->get();

        return view('individu.dossier', compact('individu', 'dossier', 'classeurs'));
    }
}