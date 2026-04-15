<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\IndividuIndependantController;
use App\Http\Controllers\AutoEnregistrementController;
use App\Http\Controllers\IndependantPerson\classeurController;
use App\Http\Controllers\IndependantPerson\documentController;
use App\Http\Controllers\IndependantPerson\dossierController;
use App\Http\Controllers\CinetPayController;


Route::get('/mes_classeurs', [classeurController::class, 'index'])->name('mes.classeurs');

// CinetPay (paiement téléchargement — URLs courtes pour la limite 120 car. de l’API)
Route::get('/cp/r/{payment}', [CinetPayController::class, 'retour'])->name('cinetpay.return');
Route::get('/cp/f/{payment}', [CinetPayController::class, 'echec'])->name('cinetpay.failed');
Route::match(['get', 'post'], '/cp/n', [CinetPayController::class, 'notify'])->name('cinetpay.notify');

Route::prefix('auto-enregistrement')->name('auto-enregistrement.')->group(function () {
    // Routes publiques (non authentifiées)
        Route::get('/inscription', [AutoEnregistrementController::class, 'showRegistrationForm'])->name('register');
        Route::post('/inscription', [AutoEnregistrementController::class, 'register'])->name('register.post');
        Route::get('/connexion', [AutoEnregistrementController::class, 'showLoginForm'])->name('login');
        Route::post('/connexion', [AutoEnregistrementController::class, 'login'])->name('login.post');
        Route::post('/deconnexion', [AutoEnregistrementController::class, 'logout'])->name('deconnexion');
    
    // Vérification email (accessible sans authentification)
    Route::get('/verify-email/{token}', [AutoEnregistrementController::class, 'verifyEmail'])->name('verify-email');
    
    // Routes AJAX pour la géolocalisation (publiques pour l'inscription)
    Route::get('/ajax/districts/{pays}', [AutoEnregistrementController::class, 'getDistricts'])->name('ajax.districts');
    Route::get('/ajax/regions/{district}', [AutoEnregistrementController::class, 'getRegions'])->name('ajax.regions');
    Route::get('/ajax/departements/{region}', [AutoEnregistrementController::class, 'getDepartements'])->name('ajax.departements');
    Route::get('/ajax/sous-prefectures/{departement}', [AutoEnregistrementController::class, 'getSousPrefectures'])->name('ajax.sous_prefectures');
    Route::get('/ajax/communes/{sousPrefecture}', [AutoEnregistrementController::class, 'getCommunes'])->name('ajax.communes');
    Route::get('/ajax/villes', [AutoEnregistrementController::class, 'getVilles'])->name('ajax.villes');
});


// Routes pour les individus indépendants authentifiés
Route::middleware('auth:individu')->prefix('individu')->name('individu.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AutoEnregistrementController::class, 'dashboard'])->name('dashboard');
    
    // Déconnexion
    Route::post('/logout', [AutoEnregistrementController::class, 'logout'])->name('logout');
    
    // Renvoyer email de vérification
    Route::post('/resend-verification', [AutoEnregistrementController::class, 'resendVerificationEmail'])->name('resend-verification');
    
    // Gestion du profil
    Route::get('/profil', [IndividuIndependantController::class, 'showProfile'])->name('profile.show');
    Route::put('/profil', [IndividuIndependantController::class, 'updateProfile'])->name('profile.update');
    
    // Gestion des documents
    Route::get('/pochette', [IndividuIndependantController::class, 'showPochette'])->name('pochette');
    Route::get('/dossier', [IndividuIndependantController::class, 'showDossier'])->name('dossier');

    // Classeurs du dossier personnel
    Route::get('/classeurs', [classeurController::class, 'index'])->name('classeurs.index');
    Route::get('/classeurs/create', [classeurController::class, 'create'])->name('classeurs.create');
    Route::post('/classeurs', [classeurController::class, 'store'])->name('classeurs.store');
    Route::get('/classeurs/{classeur}/edit', [classeurController::class, 'edit'])->name('classeurs.edit');
    Route::put('/classeurs/{classeur}', [classeurController::class, 'update'])->name('classeurs.update');
    Route::get('/classeurs/{classeur}', [classeurController::class, 'show'])->name('classeurs.show');

    // Documents dans un classeur
    Route::get('/classeurs/{classeur}/documents/create', [documentController::class, 'create'])->name('classeurs.documents.create');
    Route::post('/classeurs/{classeur}/documents', [documentController::class, 'store'])->name('classeurs.documents.store');
    Route::get('/classeurs/{classeur}/documents/{document}/download', [documentController::class, 'download'])->name('classeurs.documents.download');
});
