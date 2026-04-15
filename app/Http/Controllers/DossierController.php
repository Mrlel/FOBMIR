<?php

namespace App\Http\Controllers;

use App\Models\Dossier;
use App\Models\Pochette;
use App\Models\Menage;
use App\Models\IndividusMenage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DossierController extends Controller
{
    /**
     * Liste des dossiers d'une pochette
     */
    public function index(Menage $menage)
    {
        if (!$this->canAccessMenage($menage)) {
            return redirect()->back()->with('error', 'Vous n\'avez pas accès à ce ménage.');
        }

        $pochette = $menage->pochette;
        if (!$pochette) {
            return redirect()->route('menages.pochette.show', $menage)
                ->with('error', 'Pochette non trouvée.');
        }

        $dossiers = $pochette->dossiers()->with(['individuMenage', 'classeurs'])->withCount('classeurs')->get();
        $individus = $menage->individus;

        return view('dossiers.index', compact('dossiers', 'pochette', 'menage', 'individus'));
    }

    /**
     * Affiche le formulaire de création d'un dossier
     */
    public function create(Menage $menage)
    {
        if (!$this->canManageMenage($menage)) {
            return redirect()->back()->with('error', 'Vous n\'avez pas l\'autorisation de créer un dossier.');
        }

        $pochette = $menage->pochette;
        if (!$pochette) {
            return redirect()->route('menages.pochette.show', $menage)
                ->with('error', 'Pochette non trouvée.');
        }

        // Récupérer les individus qui n'ont pas encore de dossier
        $individusSansDossier = $menage->individus()->whereDoesntHave('dossier', function($query) use ($pochette) {
            $query->where('pochette_id', $pochette->id);
        })->get();

        return view('dossiers.create', compact('pochette', 'menage', 'individusSansDossier'));
    }

    /**
     * Enregistre un nouveau dossier
     */
    public function store(Request $request, Menage $menage)
    {
        if (!$this->canManageMenage($menage)) {
            return redirect()->back()->with('error', 'Vous n\'avez pas l\'autorisation de créer un dossier.');
        }

        $pochette = $menage->pochette;
        if (!$pochette) {
            return redirect()->route('menages.pochette.show', $menage)
                ->with('error', 'Pochette non trouvée.');
        }

        $request->validate([
            'individu_menage_id' => 'required|exists:individu_menage,id|unique:dossiers,individu_menage_id,NULL,id,pochette_id,' . $pochette->id,
            'nom' => 'nullable|string|max:200',
            'description' => 'nullable|string|max:500',
        ]);

        // Vérifier que l'individu appartient au ménage
        $individu = IndividusMenage::findOrFail($request->individu_menage_id);
        if ($individu->menage_id !== $menage->id) {
            return redirect()->back()->with('error', 'L\'individu sélectionné n\'appartient pas à ce ménage.');
        }

        // Générer le nom du dossier si non fourni
        $nom = $request->nom ?: 'Dossier de ' . $individu->prenom . ' ' . $individu->nom;

        Dossier::create([
            'pochette_id' => $pochette->id,
            'individu_menage_id' => $request->individu_menage_id,
            'nom' => $nom,
            'description' => $request->description,
        ]);

        return redirect()->route('menages.dossiers.index', $menage)
            ->with('success', 'Dossier créé avec succès.');
    }

    /**
     * Affiche les détails d'un dossier
     */
    public function show(Menage $menage, Dossier $dossier)
    {
        if (!$this->canAccessMenage($menage)) {
            return redirect()->back()->with('error', 'Vous n\'avez pas accès à ce ménage.');
        }

        // Vérifier que le dossier appartient à la pochette du ménage
        if ($dossier->pochette->menage_id !== $menage->id) {
            return redirect()->back()->with('error', 'Dossier non trouvé.');
        }

        $dossier->load(['individuMenage', 'classeurs.documents', 'pochette']);
        $classeurs = $dossier->classeurs()->withCount('documents')->get();

        return view('dossiers.show', compact('dossier', 'menage', 'classeurs'));
    }

    /**
     * Affiche le formulaire d'édition d'un dossier
     */
    public function edit(Menage $menage, Dossier $dossier)
    {
        if (!$this->canManageMenage($menage)) {
            return redirect()->back()->with('error', 'Vous n\'avez pas l\'autorisation de modifier ce dossier.');
        }

        // Vérifier que le dossier appartient à la pochette du ménage
        if ($dossier->pochette->menage_id !== $menage->id) {
            return redirect()->back()->with('error', 'Dossier non trouvé.');
        }

        return view('dossiers.edit', compact('dossier', 'menage'));
    }

    /**
     * Met à jour un dossier
     */
    public function update(Request $request, Menage $menage, Dossier $dossier)
    {
        if (!$this->canManageMenage($menage)) {
            return redirect()->back()->with('error', 'Vous n\'avez pas l\'autorisation de modifier ce dossier.');
        }

        // Vérifier que le dossier appartient à la pochette du ménage
        if ($dossier->pochette->menage_id !== $menage->id) {
            return redirect()->back()->with('error', 'Dossier non trouvé.');
        }

        $request->validate([
            'nom' => 'required|string|max:200',
            'description' => 'nullable|string|max:500',
        ]);

        $dossier->update($request->only(['nom', 'description']));

        return redirect()->route('menages.dossiers.show', [$menage, $dossier])
            ->with('success', 'Dossier modifié avec succès.');
    }

    /**
     * Supprime un dossier
     */
    public function destroy(Menage $menage, Dossier $dossier)
    {
        if (!$this->canManageMenage($menage)) {
            return redirect()->back()->with('error', 'Vous n\'avez pas l\'autorisation de supprimer ce dossier.');
        }

        // Vérifier que le dossier appartient à la pochette du ménage
        if ($dossier->pochette->menage_id !== $menage->id) {
            return redirect()->back()->with('error', 'Dossier non trouvé.');
        }

        // Vérifier qu'il n'y a pas de classeurs
        if ($dossier->classeurs()->count() > 0) {
            return redirect()->back()->with('error', 'Impossible de supprimer un dossier contenant des classeurs.');
        }

        $dossier->delete();

        return redirect()->route('menages.dossiers.index', $menage)
            ->with('success', 'Dossier supprimé avec succès.');
    }

    /**
     * Vérifie si l'utilisateur peut accéder à un ménage
     */
    private function canAccessMenage(Menage $menage)
    {
        $user = Auth::user();

        if (in_array($user->role, ['admin', 'superadmin'])) {
            return true;
        }

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
        return $this->canAccessMenage($menage);
    }
}