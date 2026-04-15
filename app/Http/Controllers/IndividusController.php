<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Document;
use App\Models\Menage;
use App\Models\IndividusMenage;
use App\Models\Quartier;
use App\Models\SousQuartier;
use App\Models\ChefQuartier;
use App\Models\ChefSousQuartier;
use App\Models\ChefVillage;
use App\Models\Village;
use App\Models\TypeDocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Traits\FiltersByVillage;


class IndividusController extends Controller
{
    use FiltersByVillage;
    public function userDashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('login.form')
                ->with('error', 'Veuillez vous connecter pour accéder au tableau de bord.');
        }

        $userId = Auth::id();
        $user = User::findOrFail($userId);
        $typeDocuments = TypeDocument::all();
        $documents = Document::with('typeDocument')
        ->where('user_id', $userId)
        ->get();
    
        return view('Individus.dashboard', compact('user','documents','typeDocuments'));
    }

    /**
     * Affiche le formulaire de création d'un individu ménage
     * Seuls les point_focal peuvent créer des individus ménages
     */
    public function create()
    {
        $user = Auth::user();
        
        // Vérifier que le point focal a une géolocalisation
        if (!$user->village_id) {
            return redirect()->back()->with('error', 'Votre compte n\'a pas de géolocalisation assignée. Veuillez contacter un administrateur.');
        }

        // Filtrer les ménages par village du point focal
        $menagesQuery = Menage::with(['sousQuartier.quartier']);
        if ($user->village_id) {
            $menagesQuery->whereHas('sousQuartier.quartier', function($q) use ($user) {
                $q->where('village_id', $user->village_id);
            });
        }
        $menages = $menagesQuery->get();
        
        return view('individus.create', compact('menages', 'user'));
    }

    /**
     * Enregistre un individu ménage avec héritage de la géolocalisation du point focal
     */
    public function store(Request $request)
    {

        $user = Auth::user();

        // Vérifier que le point focal a une géolocalisation
        if (!$user->village_id) {
            return redirect()->back()->with('error', 'Votre compte n\'a pas de géolocalisation assignée. Veuillez contacter un administrateur.');
        }

        $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:150',
            'telephone' => 'nullable|string',
            'date_naissance' => 'required|date',
            'lieu_naissance' => 'required|string',
            'numpiece' => 'required|string|max:25',
            'num_extrait_naissance' => 'required|string|max:25',
            'emploi' => 'nullable|string|max:150',
            'doc_piece' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'menage_id' => 'required|exists:menages,id',
        ]);

        // Vérifier que le ménage appartient au village du point focal
        if ($user->village_id) {
            $menage = Menage::with('sousQuartier.quartier')->findOrFail($request->menage_id);
            if ($menage->sousQuartier->quartier->village_id !== $user->village_id) {
                return redirect()->back()->with('error', 'Le ménage sélectionné n\'appartient pas à votre village.');
            }
        }

        $data = $request->except('doc_piece');
        
        // 🔥 IMPORTANT : L'individu hérite automatiquement de la géolocalisation du point focal
        $data['point_focal_id'] = $user->id;

        if ($request->hasFile('doc_piece')) {
            $data['doc_piece'] = $request->file('doc_piece')->store('pieces', 'public');
            $data['nom_piece'] = $request->file('doc_piece')->getClientOriginalName();
        }

        $individu = IndividusMenage::create($data);

        return redirect()->route('individus.index')
            ->with('success', 'Individu ménage enregistré avec succès.');
    }

    /**
     * Affiche les détails d'un individu ménage avec sa géolocalisation héritée
     */
    public function show(IndividusMenage $individu)
    {
        // Vérifier l'accès pour les points focaux
        if (!$this->isAdminOrSuperAdmin()) {
            $user = Auth::user();
            if ($user->village_id) {
                $individu->load('pointFocal');
                if ($individu->pointFocal->village_id !== $user->village_id) {
                    return redirect()->back()->with('error', 'Vous n\'avez pas accès à cet individu.');
                }
            }
        }
        
        $individu->load([
            'menage', 
            'pointFocal.village.sousPrefecture.departement.region.district.pays', 
            'pointFocal.village.commune',
            'documents.typeDocument' // Charger les documents avec leur type
        ]);
        
        // Récupérer la géolocalisation héritée
        $geolocalisation = $individu->geolocalisation;
        
        return view('individus.show', compact('individu', 'geolocalisation'));
    }

    /**
     * Liste tous les individus ménages enregistrés par le point focal connecté
     */
    public function index()
    {
        $user = Auth::user();
        
        // Pour les admins/superadmins : voir tous les individus
        // Pour les points focaux : voir uniquement ceux de leur village
        $individusQuery = IndividusMenage::with(['menage', 'pointFocal.village'])
            ->withCount('documents'); // Compter les documents
        
        if ($this->isAdminOrSuperAdmin()) {
            // Pas de filtre pour les admins
        } else {
            // Filtrer par point_focal_id qui appartient au même village
            $individusQuery->whereHas('pointFocal', function($q) use ($user) {
                $q->where('village_id', $user->village_id);
            });
        }
        
        $individus = $individusQuery->paginate(10);

        return view('individus.index', compact('individus', 'user'));
    }

    /**
     * Affiche le formulaire d'édition d'un individu ménage
     */
    public function edit(IndividusMenage $individu)
    {

        $user = Auth::user();

        // Vérifier que l'individu appartient au point focal connecté
        if ($individu->point_focal_id !== $user->id) {
            return redirect()->back()->with('error', 'Vous n\'avez pas l\'autorisation de modifier cet individu.');
        }

        // Vérifier que le point focal a une géolocalisation
        if (!$user->village_id) {
            return redirect()->back()->with('error', 'Votre compte n\'a pas de géolocalisation assignée.');
        }

        // Charger les relations nécessaires
        $individu->load(['menage.sousQuartier.quartier']);

        return view('individus.edit', compact('individu', 'user'));
    }

    /**
     * Met à jour un individu ménage
     */
    public function update(Request $request, IndividusMenage $individu)
    {

        $user = Auth::user();

        // Vérifier que l'individu appartient au point focal connecté
        if ($individu->point_focal_id !== $user->id) {
            return redirect()->back()->with('error', 'Vous n\'avez pas l\'autorisation de modifier cet individu.');
        }

        $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:150',
            'telephone' => 'nullable|string',
            'date_naissance' => 'required|date',
            'lieu_naissance' => 'required|string',
            'numpiece' => 'required|string|max:25',
            'num_extrait_naissance' => 'required|string|max:25',
            'emploi' => 'nullable|string|max:150',
            'doc_piece' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'menage_id' => 'required|exists:menages,id',
        ]);

        // Vérifier que le ménage appartient au village du point focal
        if ($user->village_id) {
            $menage = Menage::with('sousQuartier.quartier')->findOrFail($request->menage_id);
            if ($menage->sousQuartier->quartier->village_id !== $user->village_id) {
                return redirect()->back()->with('error', 'Le ménage sélectionné n\'appartient pas à votre village.');
            }
        }

        $data = $request->except('doc_piece');

        // Gérer le fichier de pièce
        if ($request->hasFile('doc_piece')) {
            // Supprimer l'ancien fichier s'il existe
            if ($individu->doc_piece) {
                Storage::disk('public')->delete($individu->doc_piece);
            }
            $data['doc_piece'] = $request->file('doc_piece')->store('pieces', 'public');
            $data['nom_piece'] = $request->file('doc_piece')->getClientOriginalName();
        }

        $individu->update($data);

        return redirect()->route('individus.show', $individu->id)
            ->with('success', 'Individu ménage modifié avec succès.');
    }

    /**
     * Supprime un individu ménage
     */
    public function destroy(IndividusMenage $individu)
    {
        $user = Auth::user();

        // Pour les points focaux : vérifier l'accès
        if (!$this->isAdminOrSuperAdmin()) {
            if ($user->village_id) {
                $individu->load('pointFocal');
                if ($individu->pointFocal->village_id !== $user->village_id) {
                    return redirect()->back()->with('error', 'Vous n\'avez pas l\'autorisation de supprimer cet individu.');
                }
            }
        }

        // Supprimer le fichier de pièce s'il existe
        if ($individu->doc_piece) {
            Storage::disk('public')->delete($individu->doc_piece);
        }

        $individu->delete();

        return redirect()->route('individus.index')
            ->with('success', 'Individu ménage supprimé avec succès.');
    }

    /**
     * Affiche la liste des documents d'un individu ménage
     */
    public function documentsIndex(IndividusMenage $individu)
    {
        // Vérifier l'accès
        if (!$this->canAccessIndividu($individu)) {
            return redirect()->back()->with('error', 'Vous n\'avez pas accès aux documents de cet individu.');
        }

        $documents = Document::where('individu_menage_id', $individu->id)
            ->with(['typeDocument', 'auteur'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('individus.documents.index', compact('individu', 'documents'));
    }

    /**
     * Affiche le formulaire de création d'un document pour un individu
     */
    public function documentsCreate(IndividusMenage $individu)
    {
        // Vérifier l'accès et les permissions
        if (!$this->canManageIndividuDocuments($individu)) {
            return redirect()->back()->with('error', 'Vous n\'avez pas l\'autorisation d\'ajouter des documents à cet individu.');
        }

        $typeDocuments = TypeDocument::orderBy('libelle')->get();
        
        return view('individus.documents.create', compact('individu', 'typeDocuments'));
    }

    /**
     * Enregistre un nouveau document pour un individu
     */
    public function documentsStore(Request $request, IndividusMenage $individu)
    {
        // Vérifier l'accès et les permissions
        if (!$this->canManageIndividuDocuments($individu)) {
            return redirect()->back()->with('error', 'Vous n\'avez pas l\'autorisation d\'ajouter des documents à cet individu.');
        }

        $request->validate([
            'libelle' => 'required|string|max:150',
            'numero' => 'nullable|string|max:25',
            'fichier' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120', // 5MB max
            'type_document_id' => 'required|exists:type_documents,id',
        ]);

        $data = $request->except('fichier');
        $data['user_id'] = Auth::id();
        $data['individu_menage_id'] = $individu->id;
        $data['menage_id'] = $individu->menage_id;
        $data['date_ajout'] = now();

        // Gérer le fichier
        if ($request->hasFile('fichier')) {
            $file = $request->file('fichier');
            $filename = time() . '_' . $file->getClientOriginalName();
            $data['fichier'] = $file->storeAs('documents/individus', $filename, 'public');
            $data['nom_fichier'] = $file->getClientOriginalName();
        }

        Document::create($data);

        return redirect()->route('individus.documents.index', $individu)
            ->with('success', 'Document ajouté avec succès.');
    }

    /**
     * Affiche les détails d'un document d'individu
     */
    public function documentsShow(IndividusMenage $individu, Document $document)
    {
        // Vérifier que le document appartient à cet individu
        if ($document->individu_menage_id !== $individu->id) {
            return redirect()->back()->with('error', 'Document non trouvé.');
        }

        // Vérifier l'accès
        if (!$this->canAccessIndividu($individu)) {
            return redirect()->back()->with('error', 'Vous n\'avez pas accès à ce document.');
        }

        $document->load(['typeDocument', 'auteur']);

        return view('individus.documents.show', compact('individu', 'document'));
    }

    /**
     * Affiche le formulaire d'édition d'un document d'individu
     */
    public function documentsEdit(IndividusMenage $individu, Document $document)
    {
        // Vérifier que le document appartient à cet individu
        if ($document->individu_menage_id !== $individu->id) {
            return redirect()->back()->with('error', 'Document non trouvé.');
        }

        // Vérifier l'accès et les permissions
        if (!$this->canManageIndividuDocuments($individu)) {
            return redirect()->back()->with('error', 'Vous n\'avez pas l\'autorisation de modifier ce document.');
        }

        $typeDocuments = TypeDocument::orderBy('libelle')->get();

        return view('individus.documents.edit', compact('individu', 'document', 'typeDocuments'));
    }

    /**
     * Met à jour un document d'individu
     */
    public function documentsUpdate(Request $request, IndividusMenage $individu, Document $document)
    {
        // Vérifier que le document appartient à cet individu
        if ($document->individu_menage_id !== $individu->id) {
            return redirect()->back()->with('error', 'Document non trouvé.');
        }

        // Vérifier l'accès et les permissions
        if (!$this->canManageIndividuDocuments($individu)) {
            return redirect()->back()->with('error', 'Vous n\'avez pas l\'autorisation de modifier ce document.');
        }

        $request->validate([
            'libelle' => 'required|string|max:150',
            'numero' => 'nullable|string|max:25',
            'fichier' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'type_document_id' => 'required|exists:type_documents,id',
        ]);

        $data = $request->except('fichier');

        // Gérer le fichier si un nouveau est uploadé
        if ($request->hasFile('fichier')) {
            // Supprimer l'ancien fichier
            if ($document->fichier) {
                Storage::disk('public')->delete($document->fichier);
            }

            $file = $request->file('fichier');
            $filename = time() . '_' . $file->getClientOriginalName();
            $data['fichier'] = $file->storeAs('documents/individus', $filename, 'public');
            $data['nom_fichier'] = $file->getClientOriginalName();
        }

        $document->update($data);

        return redirect()->route('individus.documents.show', [$individu, $document])
            ->with('success', 'Document modifié avec succès.');
    }

    /**
     * Supprime un document d'individu
     */
    public function documentsDestroy(IndividusMenage $individu, Document $document)
    {
        // Vérifier que le document appartient à cet individu
        if ($document->individu_menage_id !== $individu->id) {
            return redirect()->back()->with('error', 'Document non trouvé.');
        }

        // Vérifier l'accès et les permissions
        if (!$this->canManageIndividuDocuments($individu)) {
            return redirect()->back()->with('error', 'Vous n\'avez pas l\'autorisation de supprimer ce document.');
        }

        // Supprimer le fichier
        if ($document->fichier) {
            Storage::disk('public')->delete($document->fichier);
        }

        $document->delete();

        return redirect()->route('individus.documents.index', $individu)
            ->with('success', 'Document supprimé avec succès.');
    }

    /**
     * Télécharge un document d'individu
     */
    public function documentsDownload(IndividusMenage $individu, Document $document)
    {
        // Vérifier que le document appartient à cet individu
        if ($document->individu_menage_id !== $individu->id) {
            return redirect()->back()->with('error', 'Document non trouvé.');
        }

        // Vérifier l'accès
        if (!$this->canAccessIndividu($individu)) {
            return redirect()->back()->with('error', 'Vous n\'avez pas accès à ce document.');
        }

        if (!$document->fichier || !Storage::disk('public')->exists($document->fichier)) {
            return redirect()->back()->with('error', 'Fichier non trouvé.');
        }

        return Storage::disk('public')->download($document->fichier, $document->nom_fichier);
    }

    /**
     * Vérifie si l'utilisateur peut accéder aux informations d'un individu
     */
    private function canAccessIndividu(IndividusMenage $individu)
    {
        $user = Auth::user();

        // Admins et super admins ont accès à tout
        if ($this->isAdminOrSuperAdmin()) {
            return true;
        }

        // Points focaux : vérifier le village
        if ($user->role === 'point_focal' && $user->village_id) {
            $individu->load('pointFocal');
            return $individu->pointFocal->village_id === $user->village_id;
        }

        return false;
    }

    /**
     * Vérifie si l'utilisateur peut gérer les documents d'un individu
     */
    private function canManageIndividuDocuments(IndividusMenage $individu)
    {
        $user = Auth::user();

        // Admins et super admins peuvent gérer tous les documents
        if ($this->isAdminOrSuperAdmin()) {
            return true;
        }

        // Points focaux peuvent gérer les documents des individus de leur village
        if ($user->role === 'point_focal' && $user->village_id) {
            $individu->load('pointFocal');
            return $individu->pointFocal->village_id === $user->village_id;
        }

        return false;
    }
}
