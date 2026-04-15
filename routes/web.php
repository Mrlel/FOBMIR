<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenageController;
use App\Http\Controllers\IndividuController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\PochetteController;
use App\Http\Controllers\ClasseurController;
use App\Http\Controllers\DossierController;
use App\Http\Controllers\MenageDocumentController;
use App\Http\Controllers\Auth\authController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IndividusController;
use App\Http\Controllers\LocalisationController;
use App\Http\Controllers\Admin\LocalisationAdminController;
use App\Http\Controllers\ClasseurIndependantController;
use App\Http\Controllers\DocumentIndependantController;



// Routes pour les ménages
Route::resource('menages', MenageController::class);

// Routes pour la gestion hiérarchique des documents : Ménage → Pochette → Dossiers → Classeurs → Documents
Route::middleware('auth')->group(function () {
    // Routes pour les pochettes (liées aux ménages)
    Route::get('/menages/{menage}/pochette', [PochetteController::class, 'show'])->name('menages.pochette.show');
    Route::get('/menages/{menage}/pochette/edit', [PochetteController::class, 'edit'])->name('menages.pochette.edit');
    Route::put('/menages/{menage}/pochette', [PochetteController::class, 'update'])->name('menages.pochette.update');
    
    // Routes pour les dossiers individuels (dans les pochettes)
    Route::get('/menages/{menage}/dossiers', [DossierController::class, 'index'])->name('menages.dossiers.index');
    Route::get('/menages/{menage}/dossiers/create', [DossierController::class, 'create'])->name('menages.dossiers.create');
    Route::post('/menages/{menage}/dossiers', [DossierController::class, 'store'])->name('menages.dossiers.store');
    Route::get('/menages/{menage}/dossiers/{dossier}', [DossierController::class, 'show'])->name('menages.dossiers.show');
    Route::get('/menages/{menage}/dossiers/{dossier}/edit', [DossierController::class, 'edit'])->name('menages.dossiers.edit');
    Route::put('/menages/{menage}/dossiers/{dossier}', [DossierController::class, 'update'])->name('menages.dossiers.update');
    Route::delete('/menages/{menage}/dossiers/{dossier}', [DossierController::class, 'destroy'])->name('menages.dossiers.destroy');
    
    // Routes pour les classeurs du ménage (dans les pochettes)
    Route::get('/menages/{menage}/classeurs', [ClasseurController::class, 'index'])->name('menages.classeurs.index');
    Route::get('/menages/{menage}/classeurs/create', [ClasseurController::class, 'create'])->name('menages.classeurs.create');
    Route::post('/menages/{menage}/classeurs', [ClasseurController::class, 'store'])->name('menages.classeurs.store');
    Route::get('/menages/{menage}/classeurs/{classeur}', [ClasseurController::class, 'show'])->name('menages.classeurs.show');
    Route::get('/menages/{menage}/classeurs/{classeur}/edit', [ClasseurController::class, 'edit'])->name('menages.classeurs.edit');
    Route::put('/menages/{menage}/classeurs/{classeur}', [ClasseurController::class, 'update'])->name('menages.classeurs.update');
    Route::delete('/menages/{menage}/classeurs/{classeur}', [ClasseurController::class, 'destroy'])->name('menages.classeurs.destroy');
    
    // Routes pour les classeurs individuels (dans les dossiers)
    Route::get('/menages/{menage}/dossiers/{dossier}/classeurs/create', [ClasseurController::class, 'createInDossier'])->name('menages.dossiers.classeurs.create');
    Route::post('/menages/{menage}/dossiers/{dossier}/classeurs', [ClasseurController::class, 'storeInDossier'])->name('menages.dossiers.classeurs.store');
    Route::get('/menages/{menage}/dossiers/{dossier}/classeurs/{classeur}', [ClasseurController::class, 'showDossierClasseur'])->name('menages.dossiers.classeurs.show');
    
    // Routes pour les documents (dans les classeurs)
    Route::get('/menages/{menage}/classeurs/{classeur}/documents/create', [MenageDocumentController::class, 'create'])->name('menages.classeurs.documents.create');
    Route::post('/menages/{menage}/classeurs/{classeur}/documents', [MenageDocumentController::class, 'store'])->name('menages.classeurs.documents.store');
    Route::get('/menages/{menage}/classeurs/{classeur}/documents/{document}', [MenageDocumentController::class, 'show'])->name('menages.classeurs.documents.show');
    Route::get('/menages/{menage}/classeurs/{classeur}/documents/{document}/edit', [MenageDocumentController::class, 'edit'])->name('menages.classeurs.documents.edit');
    Route::put('/menages/{menage}/classeurs/{classeur}/documents/{document}', [MenageDocumentController::class, 'update'])->name('menages.classeurs.documents.update');
    Route::delete('/menages/{menage}/classeurs/{classeur}/documents/{document}', [MenageDocumentController::class, 'destroy'])->name('menages.classeurs.documents.destroy');
    Route::get('/menages/{menage}/classeurs/{classeur}/documents/{document}/download', [MenageDocumentController::class, 'download'])->name('menages.classeurs.documents.download');
});

// Routes pour les documents
Route::resource('documents', DocumentController::class);
Route::get('documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');

// Routes pour les utilisateurs
Route::resource('users', UserController::class);

Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
Route::get('/point_focal/dashboard', [AdminController::class, 'dashboardPointFocal'])->name('point_focal.dashboard');
Route::get('/admin/utilisateurs', [AdminController::class, 'utilisateurs'])->name('admin.utilisateurs');

Route::get('/login', [authController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [authController::class, 'login'])->name('login');
Route::post('/logout', [authController::class, 'logout'])->name('logout');
Route::get('/userDashboard', [IndividusController::class, 'userDashboard'])->name('userDashboard');

// Routes pour les individus ménages (point focal uniquement)
Route::middleware('auth')->group(function () {
    Route::get('/individus', [IndividusController::class, 'index'])->name('individus.index');
    Route::get('/individus/create', [IndividusController::class, 'create'])->name('individus.create');
    Route::post('/individus', [IndividusController::class, 'store'])->name('individus.store');
    Route::get('/individus/{individu}', [IndividusController::class, 'show'])->name('individus.show');
    Route::get('/individus/{individu}/edit', [IndividusController::class, 'edit'])->name('individus.edit');
    Route::put('/individus/{individu}', [IndividusController::class, 'update'])->name('individus.update');
    Route::delete('/individus/{individu}', [IndividusController::class, 'destroy'])->name('individus.destroy');
    
    // Routes pour les documents des individus ménages
    Route::get('/individus/{individu}/documents', [IndividusController::class, 'documentsIndex'])->name('individus.documents.index');
    Route::get('/individus/{individu}/documents/create', [IndividusController::class, 'documentsCreate'])->name('individus.documents.create');
    Route::post('/individus/{individu}/documents', [IndividusController::class, 'documentsStore'])->name('individus.documents.store');
    Route::get('/individus/{individu}/documents/{document}', [IndividusController::class, 'documentsShow'])->name('individus.documents.show');
    Route::get('/individus/{individu}/documents/{document}/edit', [IndividusController::class, 'documentsEdit'])->name('individus.documents.edit');
    Route::put('/individus/{individu}/documents/{document}', [IndividusController::class, 'documentsUpdate'])->name('individus.documents.update');
    Route::delete('/individus/{individu}/documents/{document}', [IndividusController::class, 'documentsDestroy'])->name('individus.documents.destroy');
    Route::get('/individus/{individu}/documents/{document}/download', [IndividusController::class, 'documentsDownload'])->name('individus.documents.download');
});

// Routes pour le superadmin - Gestion globale des individus ménages
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::resource('individus-menage', \App\Http\Controllers\Admin\IndividuMenageSuperAdminController::class)->parameters([
        'individus-menage' => 'individuMenage'
    ]);
    
    // Routes AJAX pour la géolocalisation en cascade
    Route::get('ajax/pays/{pays}/districts', [\App\Http\Controllers\Admin\IndividuMenageSuperAdminController::class, 'getDistricts']);
    Route::get('ajax/districts/{district}/regions', [\App\Http\Controllers\Admin\IndividuMenageSuperAdminController::class, 'getRegions']);
    Route::get('ajax/regions/{region}/departements', [\App\Http\Controllers\Admin\IndividuMenageSuperAdminController::class, 'getDepartements']);
    Route::get('ajax/departements/{departement}/sous-prefectures', [\App\Http\Controllers\Admin\IndividuMenageSuperAdminController::class, 'getSousPrefectures']);
    Route::get('ajax/sous-prefectures/{sousPrefecture}/communes', [\App\Http\Controllers\Admin\IndividuMenageSuperAdminController::class, 'getCommunes']);
    Route::get('ajax/sous-prefectures/{sousPrefecture}/villages', [\App\Http\Controllers\Admin\IndividuMenageSuperAdminController::class, 'getVillages']);
    Route::get('ajax/sous-prefectures/{sousPrefecture}/villages-non-communaux', [\App\Http\Controllers\Admin\IndividuMenageSuperAdminController::class, 'getVillagesNonCommunaux']);
    Route::get('ajax/communes/{commune}/villages', [\App\Http\Controllers\Admin\IndividuMenageSuperAdminController::class, 'getVillagesByCommune']);
    Route::get('ajax/villages/{village}/quartiers', [\App\Http\Controllers\Admin\IndividuMenageSuperAdminController::class, 'getQuartiers']);
    Route::get('ajax/quartiers/{quartier}/sous-quartiers', [\App\Http\Controllers\Admin\IndividuMenageSuperAdminController::class, 'getSousQuartiers']);
    Route::get('ajax/sous-quartiers/{sousQuartier}/menages', [\App\Http\Controllers\Admin\IndividuMenageSuperAdminController::class, 'getMenages']);
    Route::post('ajax/menages/create-rapide', [\App\Http\Controllers\Admin\IndividuMenageSuperAdminController::class, 'createMenageRapide']);
});

Route::get('/', function () {
    return view('welcome');
});


