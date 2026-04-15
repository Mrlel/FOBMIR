<?php

namespace App\Http\Controllers;

use App\Models\Classeur;
use App\Models\Pochette;
use App\Models\Dossier;
use App\Models\Menage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClasseurController extends Controller
{
    /**
     * Liste des classeurs d'une pochette
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

        $classeurs = $pochette->classeurs()->withCount('documents')->get();

        return view('classeurs.index', compact('classeurs', 'pochette', 'menage'));
    }


    public function createInDossier(Menage $menage, Dossier $dossier)
    {
        if (!$this->canManageMenage($menage)) {
            return redirect()->back()->with('error', 'Vous n\'avez pas l\'autorisation de créer un classeur.');
        }

        // Vérifier que le dossier appartient au ménage
        if ($dossier->pochette->menage_id !== $menage->id) {
            return redirect()->back()->with('error', 'Dossier non trouvé.');
        }

        $themesDisponibles = $this->getThemesDisponibles();

        return view('classeurs.create-dossier', compact('dossier', 'menage', 'themesDisponibles'));
    }

    /**
     * Enregistre un nouveau classeur dans un dossier individuel
     */
    public function storeInDossier(Request $request, Menage $menage, Dossier $dossier)
    {
        if (!$this->canManageMenage($menage)) {
            return redirect()->back()->with('error', 'Vous n\'avez pas l\'autorisation de créer un classeur.');
        }


        if ($dossier->pochette->menage_id !== $menage->id) {
            return redirect()->back()->with('error', 'Dossier non trouvé.');
        }

        $request->validate([
            'theme' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
        ]);


        $existant = Classeur::where('dossier_id', $dossier->id)
            ->where('theme', $request->theme)
            ->exists();

        if ($existant) {
            return redirect()->back()
                ->withErrors(['theme' => 'Ce thème existe déjà dans ce dossier.'])
                ->withInput();
        }

        Classeur::create([
            'dossier_id' => $dossier->id,
            'theme' => $request->theme,
            'description' => $request->description,
        ]);

        return redirect()->route('menages.dossiers.show', [$menage, $dossier])
            ->with('success', 'Classeur créé avec succès.');
    }

  
    public function showDossierClasseur(Menage $menage, Dossier $dossier, Classeur $classeur)
    {
        if (!$this->canAccessMenage($menage)) {
            return redirect()->back()->with('error', 'Vous n\'avez pas accès à ce ménage.');
        }

        if ($classeur->dossier_id !== $dossier->id) {
            return redirect()->back()->with('error', 'Classeur non trouvé.');
        }

        $classeur->load(['documents.typeDocument', 'documents.auteur', 'dossier.individuMenage']);
        $documents = $classeur->documents()->orderBy('created_at', 'desc')->paginate(10);

        return view('classeurs.show-dossier', compact('classeur', 'dossier', 'menage', 'documents'));
    }
    public function create(Menage $menage)
    {
        if (!$this->canManageMenage($menage)) {
            return redirect()->back()->with('error', 'Vous n\'avez pas l\'autorisation de créer un classeur.');
        }

        $pochette = $menage->pochette;
        if (!$pochette) {
            return redirect()->route('menages.pochette.show', $menage)
                ->with('error', 'Pochette non trouvée.');
        }

        $themesDisponibles = $this->getThemesDisponibles();

        return view('classeurs.create', compact('pochette', 'menage', 'themesDisponibles'));
    }


    public function store(Request $request, Menage $menage)
    {
        if (!$this->canManageMenage($menage)) {
            return redirect()->back()->with('error', 'Vous n\'avez pas l\'autorisation de créer un classeur.');
        }

        $pochette = $menage->pochette;
        if (!$pochette) {
            return redirect()->route('menages.pochette.show', $menage)
                ->with('error', 'Pochette non trouvée.');
        }

        $request->validate([
            'theme' => 'required|string|max:100|unique:classeurs,theme,NULL,id,pochette_id,' . $pochette->id,
            'description' => 'nullable|string|max:500',
        ]);

        Classeur::create([
            'pochette_id' => $pochette->id,
            'theme' => $request->theme,
            'description' => $request->description,
        ]);

        return redirect()->route('menages.classeurs.index', $menage)
            ->with('success', 'Classeur créé avec succès.');
    }


    public function show(Menage $menage, Classeur $classeur)
    {
        if (!$this->canAccessMenage($menage)) {
            return redirect()->back()->with('error', 'Vous n\'avez pas accès à ce ménage.');
        }


        $classeur->load(['documents.typeDocument', 'documents.auteur', 'pochette']);
        $documents = $classeur->documents()->orderBy('created_at', 'desc')->paginate(10);

        return view('classeurs.show', compact('classeur', 'menage', 'documents'));
    }

    public function edit(Menage $menage, Classeur $classeur)
    {
        if (!$this->canManageMenage($menage)) {
            return redirect()->back()->with('error', 'Vous n\'avez pas l\'autorisation de modifier ce classeur.');
        }


        if ($classeur->pochette->menage_id !== $menage->id) {
            return redirect()->back()->with('error', 'Classeur non trouvé.');
        }

        $themesDisponibles = $this->getThemesDisponibles();

        return view('classeurs.edit', compact('classeur', 'menage', 'themesDisponibles'));
    }


    public function update(Request $request, Menage $menage, Classeur $classeur)
    {
        if (!$this->canManageMenage($menage)) {
            return redirect()->back()->with('error', 'Vous n\'avez pas l\'autorisation de modifier ce classeur.');
        }


        if ($classeur->pochette->menage_id !== $menage->id) {
            return redirect()->back()->with('error', 'Classeur non trouvé.');
        }

        $request->validate([
            'theme' => 'required|string|max:100|unique:classeurs,theme,' . $classeur->id . ',id,pochette_id,' . $classeur->pochette_id,
            'description' => 'nullable|string|max:500',
        ]);

        $classeur->update($request->only(['theme', 'description']));

        return redirect()->route('menages.classeurs.show', [$menage, $classeur])
            ->with('success', 'Classeur modifié avec succès.');
    }

 
    public function destroy(Menage $menage, Classeur $classeur)
    {
        if (!$this->canManageMenage($menage)) {
            return redirect()->back()->with('error', 'Vous n\'avez pas l\'autorisation de supprimer ce classeur.');
        }


        if ($classeur->pochette->menage_id !== $menage->id) {
            return redirect()->back()->with('error', 'Classeur non trouvé.');
        }

        if ($classeur->documents()->count() > 0) {
            return redirect()->back()->with('error', 'Impossible de supprimer un classeur contenant des documents.');
        }

        $classeur->delete();

        return redirect()->route('menages.classeurs.index', $menage)
            ->with('success', 'Classeur supprimé avec succès.');
    }


    private function getThemesDisponibles()
    {
        return [
            'État civil' => 'Documents d\'état civil (actes de naissance, mariage, décès...)',
            'Identité' => 'Pièces d\'identité (CNI, passeport, permis...)',
            'Santé' => 'Documents de santé (carnets de vaccination, certificats médicaux...)',
            'Scolarité' => 'Documents scolaires (diplômes, bulletins, attestations...)',
            'Foncier' => 'Documents fonciers (titres de propriété, contrats de bail...)',
            'Justice' => 'Documents judiciaires (extraits de casier, jugements...)',
            'Travail' => 'Documents professionnels (contrats, fiches de paie...)',
            'Finances' => 'Documents financiers (relevés bancaires, attestations...)',
            'Assurance' => 'Documents d\'assurance (polices, attestations...)',
            'Autre' => 'Autres documents administratifs'
        ];
    }


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

    
    private function canManageMenage(Menage $menage)
    {
        return $this->canAccessMenage($menage);
    }
}