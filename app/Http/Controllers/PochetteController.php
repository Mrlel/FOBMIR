<?php

namespace App\Http\Controllers;

use App\Models\Pochette;
use App\Models\Menage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PochetteController extends Controller
{
    /**
     * Affiche la pochette d'un ménage
     */
    public function show(Menage $menage)
    {
        // Vérifier l'accès
        if (Auth::user()->cannot('view', $menage)) {
            return redirect()->back()->with('error', 'Vous n\'avez pas accès à ce ménage.');
        }

        // Créer la pochette si elle n'existe pas
        $pochette = $menage->pochette;
        if (!$pochette) {
            $pochette = Pochette::create([
                'menage_id' => $menage->id,
                'libelle' => 'Pochette de ' . $menage->nom_chef,
                'description' => 'Pochette automatiquement créée pour le ménage de ' . $menage->nom_chef
            ]);
        }

        $pochette->load(['classeurs.documents', 'menage']);
        
        return view('pochettes.show', compact('pochette', 'menage'));
    }

    /**
     * Affiche le formulaire d'édition de la pochette
     */
    public function edit(Menage $menage)
    {
        if (Auth::user()->cannot('update', $menage)) {
            return redirect()->back()->with('error', 'Vous n\'avez pas l\'autorisation de modifier cette pochette.');
        }

        $pochette = $menage->pochette;
        if (!$pochette) {
            return redirect()->route('menages.pochette.show', $menage)
                ->with('error', 'Pochette non trouvée.');
        }

        return view('pochettes.edit', compact('pochette', 'menage'));
    }

    /**
     * Met à jour la pochette
     */
    public function update(Request $request, Menage $menage)
    {
        if (Auth::user()->cannot('update', $menage)) {
            return redirect()->back()->with('error', 'Vous n\'avez pas l\'autorisation de modifier cette pochette.');
        }

        $request->validate([
            'libelle' => 'required|string|max:150',
            'description' => 'nullable|string|max:500',
        ]);

        $pochette = $menage->pochette;
        if (!$pochette) {
            return redirect()->route('menages.pochette.show', $menage)
                ->with('error', 'Pochette non trouvée.');
        }

        $pochette->update($request->only(['libelle', 'description']));

        return redirect()->route('menages.pochette.show', $menage)
            ->with('success', 'Pochette mise à jour avec succès.');
    }

}