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
        if (!$this->canAccessMenage($menage)) {
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
        if (!$this->canManageMenage($menage)) {
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
        if (!$this->canManageMenage($menage)) {
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

    /**
     * Vérifie si l'utilisateur peut accéder à un ménage
     */
    private function canAccessMenage(Menage $menage)
    {
        $user = Auth::user();

        // Admins et super admins ont accès à tout
        if (in_array($user->role, ['admin', 'superadmin'])) {
            return true;
        }

        // Points focaux : vérifier le village
        if ($user->role === 'point_focal' && $user->village_id) {
            $menage->load('sousQuartier.quartier');
            return $menage->sousQuartier?->quartier?->village_id === $user->village_id;
        }

        return false;
    }

    /**
     * Vérifie si l'utilisateur peut gérer un ménage
     */
    private function canManageMenage(Menage $menage)
    {
        $user = Auth::user();

        // Admins et super admins peuvent gérer tout
        if (in_array($user->role, ['admin', 'superadmin'])) {
            return true;
        }

        // Points focaux peuvent gérer les ménages de leur village
        if ($user->role === 'point_focal' && $user->village_id) {
            $menage->load('sousQuartier.quartier');
            return $menage->sousQuartier?->quartier?->village_id === $user->village_id;
        }

        return false;
    }
}