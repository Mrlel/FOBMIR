<?php

namespace App\Http\Controllers;

use App\Models\Quartier;
use App\Models\Village;
use App\Models\TypeQuartier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Traits\FiltersByVillage;

class QuartierController extends Controller
{
    
use FiltersByVillage;

public function index()
{
    $quartiersQuery = Quartier::with(['village', 'typeQuartier']);
    
    // Filtrer par village si point focal
    $villageId = $this->getUserVillageId();
    if ($villageId !== null) {
        $quartiersQuery->where('village_id', $villageId);
    }
    
    $quartiers = $quartiersQuery->get();
    return view('quartiers.index', compact('quartiers'));
}

public function create()
{
    // Filtrer les villages si point focal
    $villagesQuery = Village::query();

    $villageId = $this->getUserVillageId();
    if ($villageId !== null) {
        $villagesQuery->where('id', $villageId);
    }

    // 🔥 TRI ALPHABÉTIQUE (sans toucher à la logique)
    $villages = $villagesQuery
                    ->orderBy('nom', 'asc') // remplace 'nom' par la colonne exacte
                    ->get();
    
    $typeQuartiers = TypeQuartier::all();

    return view('quartiers.create', compact('villages', 'typeQuartiers'));
}


    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:150',
            'historique' => 'nullable|string',
            'village_id' => 'required|exists:villages,id',
            'type_quartier_id' => 'nullable|exists:type_quartiers,id',
        ]);

        // Vérifier que le village appartient au point focal
        if (!$this->isAdminOrSuperAdmin()) {
            $user = Auth::user();
            if ($user->village_id && $request->village_id != $user->village_id) {
                return redirect()->back()->with('error', 'Vous ne pouvez créer des quartiers que pour votre village.');
            }
        }

        $data = $request->all();
        $data['user_id'] = Auth::id();
        Quartier::create($data);
        return redirect()->route('quartiers.index')
            ->with('success', 'Quartier créé avec succès.');
    }

    public function show(Quartier $quartier)
    {
        // Vérifier l'accès pour les points focaux
        if (!$this->isAdminOrSuperAdmin()) {
            $user = Auth::user();
            if ($user->village_id && $quartier->village_id !== $user->village_id) {
                return redirect()->back()->with('error', 'Vous n\'avez pas accès à ce quartier.');
            }
        }
        
        return view('quartiers.show', compact('quartier'));
    }

    public function edit(Quartier $quartier)
    {
        // Vérifier l'accès pour les points focaux
        if (!$this->isAdminOrSuperAdmin()) {
            $user = Auth::user();
            if ($user->village_id && $quartier->village_id !== $user->village_id) {
                return redirect()->back()->with('error', 'Vous n\'avez pas accès à ce quartier.');
            }
        }
        
        // Filtrer les villages si point focal
        $villagesQuery = Village::query();
        $villageId = $this->getUserVillageId();
        if ($villageId !== null) {
            $villagesQuery->where('id', $villageId);
        }
        $villages = $villagesQuery->get();
        
        $typeQuartiers = TypeQuartier::all();
        return view('quartiers.edit', compact('quartier', 'villages', 'typeQuartiers'));
    }

    public function update(Request $request, Quartier $quartier)
    {
        // Vérifier l'accès pour les points focaux
        if (!$this->isAdminOrSuperAdmin()) {
            $user = Auth::user();
            if ($user->village_id && $quartier->village_id !== $user->village_id) {
                return redirect()->back()->with('error', 'Vous n\'avez pas accès à ce quartier.');
            }
        }

        $request->validate([
            'nom' => 'required|string|max:150',
            'historique' => 'nullable|string',
            'village_id' => 'required|exists:villages,id',
            'type_quartier_id' => 'nullable|exists:type_quartiers,id',
        ]);

        // Vérifier que le nouveau village appartient au point focal
        if (!$this->isAdminOrSuperAdmin()) {
            $user = Auth::user();
            if ($user->village_id && $request->village_id != $user->village_id) {
                return redirect()->back()->with('error', 'Vous ne pouvez modifier que les quartiers de votre village.');
            }
        }

        $quartier->update($request->all());
        return redirect()->route('quartiers.index')
            ->with('success', 'Quartier modifié avec succès.');
    }

    public function destroy(Quartier $quartier)
    {
        // Vérifier l'accès pour les points focaux
        if (!$this->isAdminOrSuperAdmin()) {
            $user = Auth::user();
            if ($user->village_id && $quartier->village_id !== $user->village_id) {
                return redirect()->back()->with('error', 'Vous n\'avez pas accès à ce quartier.');
            }
        }
        
        $quartier->delete();
        return redirect()->route('quartiers.index')
            ->with('success', 'Quartier supprimé avec succès.');
    }
}
