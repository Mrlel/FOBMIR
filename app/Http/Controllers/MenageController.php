<?php

namespace App\Http\Controllers;

use App\Models\Menage;
use App\Models\OrigineMenage;
use App\Models\SousQuartier;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Traits\FiltersByVillage;

class MenageController extends Controller
{
    use FiltersByVillage;
    public function index()
    {
        $menagesQuery = Menage::with(['origine', 'sousQuartier.quartier']);
        
        // Filtrer par village si point focal
        if (!$this->isAdminOrSuperAdmin()) {
            $user = Auth::user();
            if ($user->village_id) {
                $menagesQuery->whereHas('sousQuartier.quartier', function($q) use ($user) {
                    $q->where('village_id', $user->village_id);
                });
            }
        }
        
        $menages = $menagesQuery->paginate(10);
        
        // Filtrer aussi les sous-quartiers pour le formulaire
        $sousQuartiersQuery = SousQuartier::with('quartier');
        if (!$this->isAdminOrSuperAdmin()) {
            $user = Auth::user();
            if ($user->village_id) {
                $sousQuartiersQuery->whereHas('quartier', function($q) use ($user) {
                    $q->where('village_id', $user->village_id);
                });
            }
        }
        $sousQuartiers = $sousQuartiersQuery->get();
        
        $origines = OrigineMenage::all();
        return view('menages.index', compact('menages', 'sousQuartiers', 'origines'));
    }

    public function create()
    {
        // Filtrer les sous-quartiers par village si point focal
        $sousQuartiersQuery = SousQuartier::with('quartier');
        if (!$this->isAdminOrSuperAdmin()) {
            $user = Auth::user();
            if ($user->village_id) {
                $sousQuartiersQuery->whereHas('quartier', function($q) use ($user) {
                    $q->where('village_id', $user->village_id);
                });
            }
        }
        $sousQuartiers = $sousQuartiersQuery->get();
        
        $origines = OrigineMenage::all();
        return view('menages.create', compact('origines', 'sousQuartiers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom_chef' => 'required|string|max:150',
            'nb_individus' => 'nullable|integer|min:1',
            'sexe_chef' => 'required|in:M,F',
            'origine_id' => 'nullable|exists:origine_menages,id',
            'sous_quartier_id' => 'required|exists:sous_quartiers,id'
        ]);

        // Vérifier que le sous-quartier appartient au village du point focal
        if (!$this->isAdminOrSuperAdmin()) {
            $user = Auth::user();
            if ($user->village_id) {
                $sousQuartier = SousQuartier::with('quartier')->findOrFail($request->sous_quartier_id);
                if ($sousQuartier->quartier->village_id !== $user->village_id) {
                    return redirect()->back()->with('error', 'Le sous-quartier sélectionné n\'appartient pas à votre village.');
                }
            }
        }

        Menage::create($request->all());

        return redirect()->route('menages.index')
            ->with('success', 'Ménage créé avec succès.');
    }

    public function show(Menage $menage)
    {
        // Vérifier l'accès pour les points focaux
        if (!$this->isAdminOrSuperAdmin()) {
            $user = Auth::user();
            if ($user->village_id) {
                $menage->load('sousQuartier.quartier');
                if ($menage->sousQuartier->quartier->village_id !== $user->village_id) {
                    return redirect()->back()->with('error', 'Vous n\'avez pas accès à ce ménage.');
                }
            }
        }
        
        return view('menages.show', compact('menage'));
    }

    public function edit(Menage $menage)
    {
        // Vérifier l'accès pour les points focaux
        if (!$this->isAdminOrSuperAdmin()) {
            $user = Auth::user();
            if ($user->village_id) {
                $menage->load('sousQuartier.quartier');
                if ($menage->sousQuartier->quartier->village_id !== $user->village_id) {
                    return redirect()->back()->with('error', 'Vous n\'avez pas accès à ce ménage.');
                }
            }
        }
        
        // Filtrer les sous-quartiers par village si point focal
        $sousQuartiersQuery = SousQuartier::with('quartier');
        if (!$this->isAdminOrSuperAdmin()) {
            $user = Auth::user();
            if ($user->village_id) {
                $sousQuartiersQuery->whereHas('quartier', function($q) use ($user) {
                    $q->where('village_id', $user->village_id);
                });
            }
        }
        $sousQuartiers = $sousQuartiersQuery->get();
        
        $origines = OrigineMenage::all();
        return view('menages.edit', compact('menage', 'origines', 'sousQuartiers'));
    }

    public function update(Request $request, Menage $menage)
    {
        // Vérifier l'accès pour les points focaux
        if (!$this->isAdminOrSuperAdmin()) {
            $user = Auth::user();
            if ($user->village_id) {
                $menage->load('sousQuartier.quartier');
                if ($menage->sousQuartier->quartier->village_id !== $user->village_id) {
                    return redirect()->back()->with('error', 'Vous n\'avez pas accès à ce ménage.');
                }
            }
        }

        $request->validate([
            'nom_chef' => 'required|string|max:150',
            'nb_individus' => 'nullable|integer|min:1',
            'sexe_chef' => 'required|in:M,F',
            'origine_id' => 'nullable|exists:origine_menages,id',
            'sous_quartier_id' => 'required|exists:sous_quartiers,id'
        ]);

        // Vérifier que le nouveau sous-quartier appartient au village du point focal
        if (!$this->isAdminOrSuperAdmin()) {
            $user = Auth::user();
            if ($user->village_id) {
                $sousQuartier = SousQuartier::with('quartier')->findOrFail($request->sous_quartier_id);
                if ($sousQuartier->quartier->village_id !== $user->village_id) {
                    return redirect()->back()->with('error', 'Le sous-quartier sélectionné n\'appartient pas à votre village.');
                }
            }
        }

        $menage->update($request->all());

        return redirect()->route('menages.index')
            ->with('success', 'Ménage modifié avec succès.');
    }

    public function destroy(Menage $menage)
    {
        // Vérifier l'accès pour les points focaux
        if (!$this->isAdminOrSuperAdmin()) {
            $user = Auth::user();
            if ($user->village_id) {
                $menage->load('sousQuartier.quartier');
                if ($menage->sousQuartier->quartier->village_id !== $user->village_id) {
                    return redirect()->back()->with('error', 'Vous n\'avez pas accès à ce ménage.');
                }
            }
        }
        
        $menage->delete();

        return redirect()->route('menages.index')
            ->with('success', 'Ménage supprimé avec succès.');
    }
}