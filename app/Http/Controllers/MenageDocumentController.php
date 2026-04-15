<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Classeur;
use App\Models\Menage;
use App\Models\TypeDocument;
use App\Models\IndividusMenage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MenageDocumentController extends Controller
{
    /**
     * Affiche le formulaire de création d'un document dans un classeur
     */
    public function create(Menage $menage, Classeur $classeur)
    {
        if (!$this->canManageMenage($menage)) {
            return redirect()->back()->with('error', 'Vous n\'avez pas l\'autorisation d\'ajouter des documents.');
        }


        $typeDocuments = TypeDocument::orderBy('libelle')->get();
        $individus = $menage->load('sousQuartier.quartier')->sousQuartier?->quartier?->village_id 
            ? IndividusMenage::whereHas('pointFocal', function($q) use ($menage) {
                $menage->load('sousQuartier.quartier');
                $q->where('village_id', $menage->sousQuartier->quartier->village_id);
            })->where('menage_id', $menage->id)->get()
            : IndividusMenage::where('menage_id', $menage->id)->get();

        return view('menage-documents.create', compact('menage', 'classeur', 'typeDocuments', 'individus'));
    }

    /**
     * Enregistre un nouveau document dans un classeur
     */
    public function store(Request $request, Menage $menage, Classeur $classeur)
    {
        if (!$this->canManageMenage($menage)) {
            return redirect()->back()->with('error', 'Vous n\'avez pas l\'autorisation d\'ajouter des documents.');
        }

   

        $request->validate([
            'libelle' => 'required|string|max:150',
            'numero' => 'nullable|string|max:25',
            'fichier' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'type_document_id' => 'required|exists:type_documents,id',
            'individu_menage_id' => 'nullable|exists:individu_menage,id',
            'concerne_menage' => 'boolean',
        ]);

        $data = $request->except('fichier');
        $data['classeur_id'] = $classeur->id;
        $data['menage_id'] = $menage->id;
        $data['date_ajout'] = now();

        // Si le document ne concerne pas un individu spécifique, il concerne le ménage
        if (!$request->individu_menage_id || $request->concerne_menage) {
            $data['individu_menage_id'] = null;
        }

        // Gérer le fichier
        if ($request->hasFile('fichier')) {
            $file = $request->file('fichier');
            $filename = time() . '_' . $file->getClientOriginalName();
            $data['fichier'] = $file->storeAs('documents/menages/' . $menage->id, $filename, 'public');
            $data['nom_fichier'] = $file->getClientOriginalName();
        }

        Document::create($data);

        return redirect()->route('menages.classeurs.show', [$menage, $classeur])
            ->with('success', 'Document ajouté avec succès.');
    }

    /**
     * Affiche les détails d'un document
     */
    public function show(Menage $menage, Classeur $classeur, Document $document)
    {
        if (!$this->canAccessMenage($menage)) {
            return redirect()->back()->with('error', 'Vous n\'avez pas accès à ce document.');
        }

        // Vérifier que le document appartient au classeur et au ménage
        if ($document->classeur_id !== $classeur->id || $document->menage_id !== $menage->id) {
            return redirect()->back()->with('error', 'Document non trouvé.');
        }

        $document->load(['typeDocument', 'auteur', 'individuMenage']);

        return view('menage-documents.show', compact('menage', 'classeur', 'document'));
    }

    /**
     * Affiche le formulaire d'édition d'un document
     */
    public function edit(Menage $menage, Classeur $classeur, Document $document)
    {
        if (!$this->canManageMenage($menage)) {
            return redirect()->back()->with('error', 'Vous n\'avez pas l\'autorisation de modifier ce document.');
        }

        // Vérifier que le document appartient au classeur et au ménage
        if ($document->classeur_id !== $classeur->id || $document->menage_id !== $menage->id) {
            return redirect()->back()->with('error', 'Document non trouvé.');
        }

        $typeDocuments = TypeDocument::orderBy('libelle')->get();
        $individus = IndividusMenage::where('menage_id', $menage->id)->get();

        return view('menage-documents.edit', compact('menage', 'classeur', 'document', 'typeDocuments', 'individus'));
    }

    /**
     * Met à jour un document
     */
    public function update(Request $request, Menage $menage, Classeur $classeur, Document $document)
    {
        if (!$this->canManageMenage($menage)) {
            return redirect()->back()->with('error', 'Vous n\'avez pas l\'autorisation de modifier ce document.');
        }

        // Vérifier que le document appartient au classeur et au ménage
        if ($document->classeur_id !== $classeur->id || $document->menage_id !== $menage->id) {
            return redirect()->back()->with('error', 'Document non trouvé.');
        }

        $request->validate([
            'libelle' => 'required|string|max:150',
            'numero' => 'nullable|string|max:25',
            'fichier' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'type_document_id' => 'required|exists:type_documents,id',
            'individu_menage_id' => 'nullable|exists:individu_menage,id',
            'concerne_menage' => 'boolean',
        ]);

        $data = $request->except('fichier');

        // Si le document ne concerne pas un individu spécifique, il concerne le ménage
        if (!$request->individu_menage_id || $request->concerne_menage) {
            $data['individu_menage_id'] = null;
        }

        // Gérer le fichier si un nouveau est uploadé
        if ($request->hasFile('fichier')) {
            // Supprimer l'ancien fichier
            if ($document->fichier) {
                Storage::disk('public')->delete($document->fichier);
            }

            $file = $request->file('fichier');
            $filename = time() . '_' . $file->getClientOriginalName();
            $data['fichier'] = $file->storeAs('documents/menages/' . $menage->id, $filename, 'public');
            $data['nom_fichier'] = $file->getClientOriginalName();
        }

        $document->update($data);

        return redirect()->route('menages.classeurs.documents.show', [$menage, $classeur, $document])
            ->with('success', 'Document modifié avec succès.');
    }

    /**
     * Supprime un document
     */
    public function destroy(Menage $menage, Classeur $classeur, Document $document)
    {
        if (!$this->canManageMenage($menage)) {
            return redirect()->back()->with('error', 'Vous n\'avez pas l\'autorisation de supprimer ce document.');
        }

        // Vérifier que le document appartient au classeur et au ménage
        if ($document->classeur_id !== $classeur->id || $document->menage_id !== $menage->id) {
            return redirect()->back()->with('error', 'Document non trouvé.');
        }

        // Supprimer le fichier
        if ($document->fichier) {
            Storage::disk('public')->delete($document->fichier);
        }

        $document->delete();

        return redirect()->route('menages.classeurs.show', [$menage, $classeur])
            ->with('success', 'Document supprimé avec succès.');
    }

    /**
     * Télécharge un document
     */
    public function download(Menage $menage, Classeur $classeur, Document $document)
    {
        if (!$this->canAccessMenage($menage)) {
            return redirect()->back()->with('error', 'Vous n\'avez pas accès à ce document.');
        }

        // Vérifier que le document appartient au classeur et au ménage
        if ($document->classeur_id !== $classeur->id || $document->menage_id !== $menage->id) {
            return redirect()->back()->with('error', 'Document non trouvé.');
        }

        if (!$document->fichier || !Storage::disk('public')->exists($document->fichier)) {
            return redirect()->back()->with('error', 'Fichier non trouvé.');
        }

        return Storage::disk('public')->download($document->fichier, $document->nom_fichier);
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