<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SousQuartier;
use App\Models\Quartier;
use App\Models\TypeSousQuartier;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Traits\FiltersByVillage;

class SousQuartierController extends Controller
{
    use FiltersByVillage;
    public function index()
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
        
        // Filtrer les quartiers par village si point focal
        $quartiersQuery = Quartier::query();
        $villageId = $this->getUserVillageId();
        if ($villageId !== null) {
            $quartiersQuery->where('village_id', $villageId);
        }
        $quartiers = $quartiersQuery->get();
        
        $typeSousQuartiers = TypeSousQuartier::all();
        return view('sous-quartiers.index', compact('sousQuartiers', 'quartiers', 'typeSousQuartiers'));
    }
   
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'historique' => 'required',
            'quartier_id' => 'required',
            'type_sous_quartier_id' => 'required',
        ]);

        // Vérifier que le quartier appartient au village du point focal
        if (!$this->isAdminOrSuperAdmin()) {
            $user = Auth::user();
            if ($user->village_id) {
                $quartier = Quartier::findOrFail($request->quartier_id);
                if ($quartier->village_id !== $user->village_id) {
                    return redirect()->back()->with('error', 'Le quartier sélectionné n\'appartient pas à votre village.');
                }
            }
        }

        SousQuartier::create($request->all());

        return redirect()->route('sous-quartiers.index')->with('success', 'Sous-quartier créé avec succès');
    }

    public function edit($id)
    {
        $sousQuartier = SousQuartier::with('quartier')->findOrFail($id);
        
        // Vérifier l'accès pour les points focaux
        if (!$this->isAdminOrSuperAdmin()) {
            $user = Auth::user();
            if ($user->village_id && $sousQuartier->quartier->village_id !== $user->village_id) {
                return redirect()->back()->with('error', 'Vous n\'avez pas accès à ce sous-quartier.');
            }
        }
        
        // Filtrer les quartiers par village si point focal
        $quartiersQuery = Quartier::query();
        $villageId = $this->getUserVillageId();
        if ($villageId !== null) {
            $quartiersQuery->where('village_id', $villageId);
        }
        $quartiers = $quartiersQuery->get();
        
        $typeSousQuartiers = TypeSousQuartier::all();
        return view('sous-quartiers.edit', compact('sousQuartier', 'quartiers', 'typeSousQuartiers'));
    }

    public function update(Request $request, $id)
    {
        $sousQuartier = SousQuartier::with('quartier')->findOrFail($id);
        
        // Vérifier l'accès pour les points focaux
        if (!$this->isAdminOrSuperAdmin()) {
            $user = Auth::user();
            if ($user->village_id && $sousQuartier->quartier->village_id !== $user->village_id) {
                return redirect()->back()->with('error', 'Vous n\'avez pas accès à ce sous-quartier.');
            }
        }

        $request->validate([
            'nom' => 'required',
            'historique' => 'required',
            'quartier_id' => 'required',
            'type_sous_quartier_id' => 'required',
        ]);

        // Vérifier que le nouveau quartier appartient au village du point focal
        if (!$this->isAdminOrSuperAdmin()) {
            $user = Auth::user();
            if ($user->village_id) {
                $quartier = Quartier::findOrFail($request->quartier_id);
                if ($quartier->village_id !== $user->village_id) {
                    return redirect()->back()->with('error', 'Le quartier sélectionné n\'appartient pas à votre village.');
                }
            }
        }

        $sousQuartier->update($request->all());
        return redirect()->route('sous-quartiers.index')->with('success', 'Sous-quartier mis à jour avec succès');
    }

    public function destroy($id)
    {
        $sousQuartier = SousQuartier::with('quartier')->findOrFail($id);
        
        // Vérifier l'accès pour les points focaux
        if (!$this->isAdminOrSuperAdmin()) {
            $user = Auth::user();
            if ($user->village_id && $sousQuartier->quartier->village_id !== $user->village_id) {
                return redirect()->back()->with('error', 'Vous n\'avez pas accès à ce sous-quartier.');
            }
        }
        
        $sousQuartier->delete();
        return redirect()->route('sous-quartiers.index')->with('success', 'Sous-quartier supprimé avec succès');
    }
}
