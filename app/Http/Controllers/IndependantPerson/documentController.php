<?php

namespace App\Http\Controllers\IndependantPerson;

use App\Http\Controllers\Controller;
use App\Models\Classeur;
use App\Models\Document;
use App\Models\TypeDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class documentController extends Controller
{
    public function create(Classeur $classeur)
    {
        $individu = Auth::guard('individu')->user();
        if (!$individu) {
            return redirect()->route('auto-enregistrement.login');
        }

        $dossier = $individu->dossier;
        if (!$dossier || $classeur->dossier_id !== $dossier->id) {
            return redirect()->route('individu.classeurs.index')
                ->with('error', 'Classeur non trouvé.');
        }

        $typeDocuments = TypeDocument::orderBy('libelle')->get();

        return view('documents.individus_independant.create', compact('individu', 'dossier', 'classeur', 'typeDocuments'));
    }

    public function store(Request $request, Classeur $classeur)
    {
        $individu = Auth::guard('individu')->user();
        if (!$individu) {
            return redirect()->route('auto-enregistrement.login');
        }

        $dossier = $individu->dossier;
        if (!$dossier || $classeur->dossier_id !== $dossier->id) {
            return redirect()->route('individu.classeurs.index')
                ->with('error', 'Classeur non trouvé.');
        }

        $data = $request->validate([
            'libelle'          => 'required|string|max:150',
            'numero'           => 'nullable|string|max:25',
            'fichier'          => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx,ppt|max:2048',
            'type_document_id' => 'required|exists:type_documents,id',
        ]);

        unset($data['fichier']);
        $data['classeur_id']            = $classeur->id;
        $data['individu_independant_id'] = $individu->id;
        $data['date_ajout']              = now();

        if ($request->hasFile('fichier')) {
            $file                = $request->file('fichier');
            $filename            = time() . '_' . $file->getClientOriginalName();
            $data['fichier']     = $file->storeAs('documents/individus_independants/' . $individu->id, $filename, 'public');
            $data['nom_fichier'] = $file->getClientOriginalName();
        }

        Document::create($data);

        return redirect()->route('individu.classeurs.show', $classeur)
            ->with('success', 'Document ajouté avec succès.');
    }

    public function download(Classeur $classeur, Document $document)
    {
        $individu = Auth::guard('individu')->user();
        if (!$individu) {
            return redirect()->route('auto-enregistrement.login');
        }

        $dossier = $individu->dossier;
        if (
            !$dossier ||
            $classeur->dossier_id !== $dossier->id ||
            $document->classeur_id !== $classeur->id
        ) {
            return redirect()->route('individu.classeurs.index')
                ->with('error', 'Document non trouvé.');
        }

        if (!$document->fichier || !Storage::disk('public')->exists($document->fichier)) {
            return redirect()->back()->with('error', 'Fichier non trouvé.');
        }

        // L'individu télécharge un document de son propre dossier (propriété déjà
        // vérifiée ci-dessus) : aucun paiement n'est requis ici. Le parcours d'achat
        // par un tiers externe (non propriétaire) est géré séparément par
        // DocumentPaymentController (routes /document/{document}/buy).
        return Storage::disk('public')->download($document->fichier, $document->nom_fichier ?? basename($document->fichier));
    }
}
