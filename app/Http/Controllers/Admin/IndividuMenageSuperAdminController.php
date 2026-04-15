<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IndividusMenage;
use App\Models\Menage;
use App\Models\User;
use App\Models\Pays;
use App\Models\District;
use App\Models\Region;
use App\Models\Departement;
use App\Models\SousPrefecture;
use App\Models\Commune;
use App\Models\Village;
use App\Models\Quartier;
use App\Models\SousQuartier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class IndividuMenageSuperAdminController extends Controller
{
    /**
     * Middleware pour vérifier que l'utilisateur est superadmin
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'superadmin') {
                abort(403, 'Accès réservé aux super administrateurs');
            }
            return $next($request);
        });
    }

    /**
     * Liste tous les individus ménages (vue globale pour superadmin)
     */
    public function index(Request $request)
    {
        $query = IndividusMenage::with([
            'menage.sousQuartier.quartier.village',
            'pointFocal'
        ])->withCount('documents');

        // Filtres de recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('telephone', 'like', "%{$search}%")
                  ->orWhere('numpiece', 'like', "%{$search}%");
            });
        }

        // Filtre par village
        if ($request->filled('village_id')) {
            $query->whereHas('menage.sousQuartier.quartier', function($q) use ($request) {
                $q->where('village_id', $request->village_id);
            });
        }

        // Filtre par point focal
        if ($request->filled('point_focal_id')) {
            $query->where('point_focal_id', $request->point_focal_id);
        }

        // Filtre par ménage
        if ($request->filled('menage_id')) {
            $query->where('menage_id', $request->menage_id);
        }

        $individus = $query->orderBy('created_at', 'desc')->paginate(20);

        // Données pour les filtres
        $villages = Village::orderBy('nom')->get();
        $pointsFocaux = User::where('role', 'point_focal')->orderBy('nom')->get();
        $menages = Menage::with('sousQuartier')->orderBy('nom_chef')->get();

        return view('admin.individus-menage.index', compact('individus', 'villages', 'pointsFocaux', 'menages'));
    }

    /**
     * Affiche le formulaire de création avec géolocalisation complète
     */
    public function create()
    {
        $pays = Pays::orderBy('nom')->get();
        $pointsFocaux = User::where('role', 'point_focal')->orderBy('nom')->get();
        
        return view('admin.individus-menage.create', compact('pays', 'pointsFocaux'));
    }

    /**
     * Enregistre un individu ménage avec géolocalisation complète
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:150',
            'telephone' => 'nullable|string',
            'date_naissance' => 'required|date',
            'lieu_naissance' => 'required|string',
            'numpiece' => 'required|string|max:25|unique:individu_menage,numpiece',
            'num_extrait_naissance' => 'required|string|max:25',
            'emploi' => 'nullable|string|max:150',
            'doc_piece' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            
            // Géolocalisation complète
            'pays_id' => 'required|exists:pays,id',
            'district_id' => 'nullable|exists:districts,id',
            'region_id' => 'nullable|exists:regions,id',
            'departement_id' => 'nullable|exists:departements,id',
            'sous_prefecture_id' => 'nullable|exists:sous_prefectures,id',
            'commune_id' => 'nullable|exists:communes,id',
            'village_id' => 'nullable|exists:villages,id',
            'quartier_id' => 'nullable|exists:quartiers,id',
            'sous_quartier_id' => 'nullable|exists:sous_quartiers,id',
            'menage_id' => 'required|exists:menages,id',
            
            // Point focal (optionnel pour superadmin)
            'point_focal_id' => 'nullable|exists:users,id',
        ]);

        $data = $request->except('doc_piece');
        
        // Si aucun point focal n'est spécifié, utiliser le superadmin connecté
        if (!$request->filled('point_focal_id')) {
            $data['point_focal_id'] = Auth::id();
        }

        // Gérer le fichier de pièce
        if ($request->hasFile('doc_piece')) {
            $data['doc_piece'] = $request->file('doc_piece')->store('pieces', 'public');
            $data['nom_piece'] = $request->file('doc_piece')->getClientOriginalName();
        }

        $individu = IndividusMenage::create($data);

        return redirect()->route('admin.individus-menage.show', $individu)
            ->with('success', 'Individu ménage créé avec succès.');
    }

    /**
     * Affiche les détails d'un individu ménage
     */
    public function show(IndividusMenage $individuMenage)
    {
        $individuMenage->load([
            'menage.sousQuartier.quartier.village.sousPrefecture.departement.region.district.pays',
            'menage.sousQuartier.quartier.village.commune',
            'pointFocal',
            'documents.typeDocument'
        ]);

        return view('admin.individus-menage.show', compact('individuMenage'));
    }

    /**
     * Affiche le formulaire d'édition
     */
    public function edit(IndividusMenage $individuMenage)
    {
        $individuMenage->load([
            'menage.sousQuartier.quartier.village'
        ]);

        $pays = Pays::orderBy('nom')->get();
        $pointsFocaux = User::where('role', 'point_focal')->orderBy('nom')->get();

        // Charger les données de géolocalisation existantes
        $menage = $individuMenage->menage;
        $sousQuartier = $menage->sousQuartier;
        $quartier = $sousQuartier->quartier ?? null;
        $village = $quartier->village ?? null;

        $districts = [];
        $regions = [];
        $departements = [];
        $sousPrefectures = [];
        $communes = [];
        $villages = [];
        $quartiers = [];
        $sousQuartiers = [];
        $menages = [];

        if ($village) {
            $sousPrefecture = $village->sousPrefecture;
            $departement = $sousPrefecture->departement ?? null;
            $region = $departement->region ?? null;
            $district = $region->district ?? null;
            $paysId = $district->pays_id ?? null;

            if ($paysId) {
                $districts = District::where('pays_id', $paysId)->orderBy('nom')->get();
            }
            if ($district) {
                $regions = Region::where('district_id', $district->id)->orderBy('nom')->get();
            }
            if ($region) {
                $departements = Departement::where('region_id', $region->id)->orderBy('nom')->get();
            }
            if ($departement) {
                $sousPrefectures = SousPrefecture::where('departement_id', $departement->id)->orderBy('nom')->get();
            }
            if ($sousPrefecture) {
                $communes = Commune::where('sous_prefecture_id', $sousPrefecture->id)->orderBy('nom')->get();
                $villages = Village::where('sous_prefecture_id', $sousPrefecture->id)->orderBy('nom')->get();
            }
            if ($village) {
                $quartiers = Quartier::where('village_id', $village->id)->orderBy('nom')->get();
            }
            if ($quartier) {
                $sousQuartiers = SousQuartier::where('quartier_id', $quartier->id)->orderBy('nom')->get();
            }
            if ($sousQuartier) {
                $menages = Menage::where('sous_quartier_id', $sousQuartier->id)->orderBy('nom_chef')->get();
            }
        }

        return view('admin.individus-menage.edit', compact(
            'individuMenage', 'pays', 'pointsFocaux', 'districts', 'regions', 
            'departements', 'sousPrefectures', 'communes', 'villages', 
            'quartiers', 'sousQuartiers', 'menages'
        ));
    }

    /**
     * Met à jour un individu ménage
     */
    public function update(Request $request, IndividusMenage $individuMenage)
    {
        $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:150',
            'telephone' => 'nullable|string',
            'date_naissance' => 'required|date',
            'lieu_naissance' => 'required|string',
            'numpiece' => 'required|string|max:25|unique:individu_menage,numpiece,' . $individuMenage->id,
            'num_extrait_naissance' => 'required|string|max:25',
            'emploi' => 'nullable|string|max:150',
            'doc_piece' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            
            // Géolocalisation complète
            'pays_id' => 'required|exists:pays,id',
            'district_id' => 'nullable|exists:districts,id',
            'region_id' => 'nullable|exists:regions,id',
            'departement_id' => 'nullable|exists:departements,id',
            'sous_prefecture_id' => 'nullable|exists:sous_prefectures,id',
            'commune_id' => 'nullable|exists:communes,id',
            'village_id' => 'nullable|exists:villages,id',
            'quartier_id' => 'nullable|exists:quartiers,id',
            'sous_quartier_id' => 'nullable|exists:sous_quartiers,id',
            'menage_id' => 'required|exists:menages,id',
            
            // Point focal
            'point_focal_id' => 'nullable|exists:users,id',
        ]);

        $data = $request->except('doc_piece');

        // Gérer le fichier de pièce
        if ($request->hasFile('doc_piece')) {
            // Supprimer l'ancien fichier
            if ($individuMenage->doc_piece) {
                Storage::disk('public')->delete($individuMenage->doc_piece);
            }
            $data['doc_piece'] = $request->file('doc_piece')->store('pieces', 'public');
            $data['nom_piece'] = $request->file('doc_piece')->getClientOriginalName();
        }

        $individuMenage->update($data);

        return redirect()->route('admin.individus-menage.show', $individuMenage)
            ->with('success', 'Individu ménage modifié avec succès.');
    }

    /**
     * Supprime un individu ménage
     */
    public function destroy(IndividusMenage $individuMenage)
    {
        // Supprimer le fichier de pièce
        if ($individuMenage->doc_piece) {
            Storage::disk('public')->delete($individuMenage->doc_piece);
        }

        // Supprimer les documents associés
        foreach ($individuMenage->documents as $document) {
            if ($document->fichier) {
                Storage::disk('public')->delete($document->fichier);
            }
            $document->delete();
        }

        $individuMenage->delete();

        return redirect()->route('admin.individus-menage.index')
            ->with('success', 'Individu ménage supprimé avec succès.');
    }

    /**
     * AJAX: Récupère les districts d'un pays
     */
    public function getDistricts($paysId)
    {
        $districts = District::where('pays_id', $paysId)->orderBy('nom')->get();
        return response()->json($districts);
    }

    /**
     * AJAX: Récupère les régions d'un district
     */
    public function getRegions($districtId)
    {
        $regions = Region::where('district_id', $districtId)->orderBy('nom')->get();
        return response()->json($regions);
    }

    /**
     * AJAX: Récupère les départements d'une région
     */
    public function getDepartements($regionId)
    {
        $departements = Departement::where('region_id', $regionId)->orderBy('nom')->get();
        return response()->json($departements);
    }

    /**
     * AJAX: Récupère les sous-préfectures d'un département
     */
    public function getSousPrefectures($departementId)
    {
        $sousPrefectures = SousPrefecture::where('departement_id', $departementId)->orderBy('nom')->get();
        return response()->json($sousPrefectures);
    }

    /**
     * AJAX: Récupère les communes d'une sous-préfecture
     */
    public function getCommunes($sousPrefectureId)
    {
        $communes = Commune::where('sous_prefecture_id', $sousPrefectureId)->orderBy('nom')->get();
        return response()->json($communes);
    }

    /**
     * AJAX: Récupère les villages d'une sous-préfecture
     */
    public function getVillages($sousPrefectureId)
    {
        $villages = Village::where('sous_prefecture_id', $sousPrefectureId)->orderBy('nom')->get();
        return response()->json($villages);
    }

    /**
     * AJAX: Récupère uniquement les villages non-communaux d'une sous-préfecture
     */
    public function getVillagesNonCommunaux($sousPrefectureId)
    {
        $villages = Village::where('sous_prefecture_id', $sousPrefectureId)
            ->whereNull('commune_id')
            ->orderBy('nom')
            ->get();
        return response()->json($villages);
    }

    /**
     * AJAX: Récupère les villages d'une commune
     */
    public function getVillagesByCommune($communeId)
    {
        $villages = Village::where('commune_id', $communeId)->orderBy('nom')->get();
        return response()->json($villages);
    }

    /**
     * AJAX: Récupère les quartiers d'un village
     */
    public function getQuartiers($villageId)
    {
        $quartiers = Quartier::where('village_id', $villageId)->orderBy('nom')->get();
        return response()->json($quartiers);
    }

    /**
     * AJAX: Récupère les sous-quartiers d'un quartier
     */
    public function getSousQuartiers($quartierId)
    {
        $sousQuartiers = SousQuartier::where('quartier_id', $quartierId)->orderBy('nom')->get();
        return response()->json($sousQuartiers);
    }

    /**
     * AJAX: Récupère les ménages d'un sous-quartier
     */
    public function getMenages($sousQuartierId)
    {
        $menages = Menage::where('sous_quartier_id', $sousQuartierId)
            ->orderBy('nom_chef')
            ->get()
            ->map(function($menage) {
                return [
                    'id' => $menage->id,
                    'nom_chef' => $menage->nom_chef,
                    'nb_individus' => $menage->nb_individus,
                    'sexe_chef' => $menage->sexe_chef
                ];
            });
        return response()->json($menages);
    }

    /**
     * AJAX: Création rapide d'un ménage
     */
    public function createMenageRapide(Request $request)
    {
        $request->validate([
            'nom_chef' => 'required|string|max:255',
            'sous_quartier_id' => 'required|exists:sous_quartiers,id',
            'sexe_chef' => 'nullable|in:M,F',
            'nb_individus' => 'nullable|integer|min:1'
        ]);

        $menage = Menage::create([
            'nom_chef' => $request->nom_chef,
            'sous_quartier_id' => $request->sous_quartier_id,
            'sexe_chef' => $request->sexe_chef,
            'nb_individus' => $request->nb_individus ?? 1
        ]);

        return response()->json([
            'success' => true,
            'menage' => [
                'id' => $menage->id,
                'nom_chef' => $menage->nom_chef,
                'nb_individus' => $menage->nb_individus,
                'sexe_chef' => $menage->sexe_chef
            ]
        ]);
    }
}