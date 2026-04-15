<?php

namespace App\Http\Controllers;

use App\Models\Pays;
use App\Models\District;
use App\Models\Region;
use App\Models\Departement;
use App\Models\SousPrefecture;
use App\Models\Commune;
use App\Models\Village;
use App\Models\Quartier;
use App\Models\SousQuartier;
use App\Models\Menage;

class LocalisationController extends Controller
{
    // Recherche basique par ?q= pour Tom Select
    private function applySearch($query)
    {
        $term = request('q');
        if ($term) {
            $query->where('nom', 'like', '%' . $term . '%');
        }
        return $query;
    }

    public function pays()
    {
        $q = Pays::select('id','nom')->orderBy('nom');
        return response()->json($this->applySearch($q)->limit(50)->get());
    }

    public function districts(Pays $pays)
    {
        $q = $pays->districts()->select('id','nom')->orderBy('nom');
        return response()->json($this->applySearch($q)->limit(50)->get());
    }

    public function regions(District $district)
    {
        $q = $district->regions()->select('id','nom')->orderBy('nom');
        return response()->json($this->applySearch($q)->limit(50)->get());
    }

    public function departements(Region $region)
    {
        $q = $region->departements()->select('id','nom')->orderBy('nom');
        return response()->json($this->applySearch($q)->limit(50)->get());
    }

    public function sousPrefectures(Departement $departement)
    {
        $q = $departement->sousPrefectures()->select('id','nom')->orderBy('nom');
        return response()->json($this->applySearch($q)->limit(50)->get());
    }

    public function communes(SousPrefecture $sousPrefecture)
    {
        $q = $sousPrefecture->communes()->select('id','nom')->orderBy('nom');
        return response()->json($this->applySearch($q)->limit(50)->get());
    }

    public function villagesBySousPrefecture(SousPrefecture $sousPrefecture)
    {
        $q = $sousPrefecture->villages()->select('id','nom')->orderBy('nom');
        return response()->json($this->applySearch($q)->limit(50)->get());
    }

    public function villagesNonCommunaux(SousPrefecture $sousPrefecture)
    {
        $q = $sousPrefecture->villages()->whereNull('commune_id')->select('id','nom')->orderBy('nom');
        return response()->json($this->applySearch($q)->limit(50)->get());
    }

    public function villagesByCommune(Commune $commune)
    {
        $q = $commune->villages()->select('id','nom')->orderBy('nom');
        return response()->json($this->applySearch($q)->limit(50)->get());
    }

    public function quartiers(Village $village)
    {
        $q = $village->quartiers()->select('id','nom')->orderBy('nom');
        return response()->json($this->applySearch($q)->limit(50)->get());
    }

    public function sousQuartiers(Quartier $quartier)
    {
        $q = $quartier->sousQuartiers()->select('id','nom')->orderBy('nom');
        return response()->json($this->applySearch($q)->limit(50)->get());
    }

    public function menages(SousQuartier $sousQuartier)
    {
        $q = $sousQuartier->menages()->select('id','nom_chef as nom')->orderBy('nom');
        return response()->json($this->applySearch($q)->limit(50)->get());
    }
}


