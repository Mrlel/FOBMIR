<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocalisationController;
use App\Http\Controllers\ChefSousQuartierController;
use App\Http\Controllers\SousQuartierController;
use App\Http\Controllers\QuartierController;
use App\Http\Controllers\ChefVillageController;
use App\Http\Controllers\ChefQuartierController;









Route::resource('chefs-sous-quartier', ChefSousQuartierController::class);
Route::resource('sous-quartiers', SousQuartierController::class);
Route::resource('quartiers', QuartierController::class);
Route::resource('/chefs-village', ChefVillageController::class)
    ->parameters(['chefs-village' => 'chefVillage']);
Route::resource('chefs-quartier', ChefQuartierController::class);

// AJAX Localisation
Route::get('/ajax/pays', [LocalisationController::class, 'pays'])->name('ajax.pays');
Route::get('/ajax/pays/{pays}/districts', [LocalisationController::class, 'districts'])->name('ajax.districts');
Route::get('/ajax/districts/{district}/regions', [LocalisationController::class, 'regions'])->name('ajax.regions');
Route::get('/ajax/regions/{region}/departements', [LocalisationController::class, 'departements'])->name('ajax.departements');
Route::get('/ajax/departements/{departement}/sous-prefectures', [LocalisationController::class, 'sousPrefectures'])->name('ajax.sous_prefectures');
Route::get('/ajax/sous-prefectures/{sousPrefecture}/communes', [LocalisationController::class, 'communes'])->name('ajax.communes');
Route::get('/ajax/sous-prefectures/{sousPrefecture}/villages', [LocalisationController::class, 'villagesBySousPrefecture'])->name('ajax.villages.by_sp');
Route::get('/ajax/sous-prefectures/{sousPrefecture}/villages-non-communaux', [LocalisationController::class, 'villagesNonCommunaux'])->name('ajax.villages.non_communaux');
Route::get('/ajax/communes/{commune}/villages', [LocalisationController::class, 'villagesByCommune'])->name('ajax.villages.by_commune');
Route::get('/ajax/villages/{village}/quartiers', [LocalisationController::class, 'quartiers'])->name('ajax.quartiers');
Route::get('/ajax/quartiers/{quartier}/sous-quartiers', [LocalisationController::class, 'sousQuartiers'])->name('ajax.sous_quartiers');
Route::get('/ajax/sous-quartiers/{sousQuartier}/menages', [LocalisationController::class, 'menages'])->name('ajax.menages');

// AJAX pour charger les types et origines
Route::get('/ajax/types-quartiers', function() { return response()->json(\App\Models\TypeQuartier::select('id','libelle as nom')->orderBy('libelle')->get()); })->name('ajax.types_quartiers');
Route::get('/ajax/types-sous-quartiers', function() { return response()->json(\App\Models\TypeSousQuartier::select('id','libelle as nom')->orderBy('libelle')->get()); })->name('ajax.types_sous_quartiers');
Route::get('/ajax/origines-menages', function() { return response()->json(\App\Models\OrigineMenage::select('id','libelle as nom')->orderBy('libelle')->get()); })->name('ajax.origines_menages');

// Création rapide AJAX (minimale)
Route::post('/ajax/create/pays', function() { $data=request()->validate(['nom'=>'required|string']); $m=\App\Models\Pays::create(['nom'=>$data['nom']]); return response()->json($m); })->name('ajax.create.pays');
Route::post('/ajax/create/districts', function() { $v=request()->validate(['nom'=>'required','pays_id'=>'required|exists:pays,id']); $m=\App\Models\District::create(['nom'=>$v['nom'],'pays_id'=>$v['pays_id']]); return response()->json($m); })->name('ajax.create.districts');
Route::post('/ajax/create/regions', function() { $v=request()->validate(['nom'=>'required','district_id'=>'required|exists:districts,id']); $m=\App\Models\Region::create(['nom'=>$v['nom'],'district_id'=>$v['district_id'],'code'=>uniqid()]); return response()->json($m); })->name('ajax.create.regions');
Route::post('/ajax/create/departements', function() { $v=request()->validate(['nom'=>'required','region_id'=>'required|exists:regions,id','code'=>'nullable']); $m=\App\Models\Departement::create(['nom'=>$v['nom'],'region_id'=>$v['region_id'],'code'=>$v['code']??uniqid()]); return response()->json($m); })->name('ajax.create.departements');
Route::post('/ajax/create/sous-prefectures', function() { $v=request()->validate(['nom'=>'required','departement_id'=>'required|exists:departements,id','code'=>'nullable']); $m=\App\Models\SousPrefecture::create(['nom'=>$v['nom'],'departement_id'=>$v['departement_id'],'code'=>$v['code']??uniqid()]); return response()->json($m); })->name('ajax.create.sous_prefectures');
Route::post('/ajax/create/communes', function() { $v=request()->validate(['nom'=>'required','sous_prefecture_id'=>'required|exists:sous_prefectures,id','code'=>'nullable']); $m=\App\Models\Commune::create(['nom'=>$v['nom'],'sous_prefecture_id'=>$v['sous_prefecture_id'],'code'=>$v['code']??uniqid()]); return response()->json($m); })->name('ajax.create.communes');
Route::post('/ajax/create/villages', function() { $v=request()->validate(['nom'=>'required','sous_prefecture_id'=>'required|exists:sous_prefectures,id','commune_id'=>'nullable|exists:communes,id','code'=>'nullable']); $m=\App\Models\Village::create(['nom'=>$v['nom'],'sous_prefecture_id'=>$v['sous_prefecture_id'],'commune_id'=>$v['commune_id']??null,'code'=>$v['code']??uniqid()]); return response()->json($m); })->name('ajax.create.villages');
Route::post('/ajax/create/quartiers', function() { 
    if (!auth()->check()) {
        return response()->json(['error' => 'Non authentifié'], 401);
    }
    $v=request()->validate(['nom'=>'required','village_id'=>'required|exists:villages,id','historique'=>'nullable|string','type_quartier_id'=>'nullable|exists:type_quartiers,id']); 
    $m=\App\Models\Quartier::create([
        'nom'=>$v['nom'],
        'village_id'=>$v['village_id'],
        'historique'=>$v['historique']??null,
        'type_quartier_id'=>$v['type_quartier_id']??null,
        'user_id'=>auth()->id()
    ]); 
    return response()->json($m); 
})->middleware('auth')->name('ajax.create.quartiers');
Route::post('/ajax/create/sous-quartiers', function() { $v=request()->validate(['nom'=>'required','quartier_id'=>'required|exists:quartiers,id','historique'=>'nullable|string','type_sous_quartier_id'=>'nullable|exists:type_sous_quartiers,id']); $m=\App\Models\SousQuartier::create(['nom'=>$v['nom'],'quartier_id'=>$v['quartier_id'],'historique'=>$v['historique']??null,'type_sous_quartier_id'=>$v['type_sous_quartier_id']??null]); return response()->json($m); })->name('ajax.create.sous_quartiers');
Route::post('/ajax/create/menages', function() { $v=request()->validate(['nom_chef'=>'required','sous_quartier_id'=>'required|exists:sous_quartiers,id','nb_individus'=>'nullable|integer','sexe_chef'=>'nullable|string|in:M,F','origine_id'=>'nullable|exists:origine_menages,id']); $m=\App\Models\Menage::create(['nom_chef'=>$v['nom_chef'],'sous_quartier_id'=>$v['sous_quartier_id'],'nb_individus'=>$v['nb_individus']??null,'sexe_chef'=>$v['sexe_chef']??null,'origine_id'=>$v['origine_id']??null]); return response()->json(['id'=>$m->id,'nom_chef'=>$m->nom_chef,'nom'=>$m->nom_chef]); })->name('ajax.create.menages');
