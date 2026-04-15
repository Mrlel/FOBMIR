<?php

namespace App\Http\Controllers\IndependantPerson;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Classeur;
use App\Models\Dossier;

class classeurController extends Controller
{
    public function index()
    {
        $individu = Auth::guard('individu')->user();
        if (!$individu) {
            return redirect()->route('auto-enregistrement.login');
        }

        $dossier = $individu->dossier;
        if (!$dossier) {
            $dossier = $individu->creerStructureDocuments();
        }

        $classeurs = $dossier->classeurs()->withCount('documents')->orderBy('created_at', 'desc')->get();

        return view('classeurs.individus_independant.index', compact('individu', 'dossier', 'classeurs'));
    }

    public function create()
    {
        $individu = Auth::guard('individu')->user();
        if (!$individu) {
            return redirect()->route('auto-enregistrement.login');
        }

        $dossier = $individu->dossier;
        if (!$dossier) {
            $dossier = $individu->creerStructureDocuments();
        }

        $themesDisponibles = $this->getThemesDisponibles();

        return view('classeurs.individus_independant.create', compact('individu', 'dossier', 'themesDisponibles'));
    }

    public function store(Request $request)
    {
        $individu = Auth::guard('individu')->user();
        if (!$individu) {
            return redirect()->route('auto-enregistrement.login');
        }

        $dossier = $individu->dossier;
        if (!$dossier) {
            $dossier = $individu->creerStructureDocuments();
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
                ->withErrors(['theme' => 'Ce theme existe deja dans votre dossier.'])
                ->withInput();
        }

        Classeur::create([
            'dossier_id' => $dossier->id,
            'theme' => $request->theme,
            'description' => $request->description,
        ]);

        return redirect()->route('individu.classeurs.index')
            ->with('success', 'Classeur cree avec succes.');
    }

    public function show(Classeur $classeur)
    {
        $individu = Auth::guard('individu')->user();
        if (!$individu) {
            return redirect()->route('auto-enregistrement.login');
        }

        $dossier = $individu->dossier;
        if (!$dossier) {
            return redirect()->route('individu.classeurs.index')
                ->with('error', 'Dossier non trouve.');
        }

        if ($classeur->dossier_id !== $dossier->id) {
            return redirect()->route('individu.classeurs.index')
                ->with('error', 'Classeur non trouve.');
        }

        $classeur->load(['documents.typeDocument']);
        $documents = $classeur->documents()->orderBy('created_at', 'desc')->get();

        return view('classeurs.individus_independant.show', compact('individu', 'dossier', 'classeur', 'documents'));
    }

    public function edit(Classeur $classeur)
    {
        $individu = Auth::guard('individu')->user();
        if (!$individu) {
            return redirect()->route('auto-enregistrement.login');
        }

        $dossier = $individu->dossier;
        if (!$dossier) {
            return redirect()->route('individu.classeurs.index')
                ->with('error', 'Dossier non trouve.');
        }

        if ($classeur->dossier_id !== $dossier->id) {
            return redirect()->route('individu.classeurs.index')
                ->with('error', 'Classeur non trouve.');
        }

        $themesDisponibles = $this->getThemesDisponibles();

        return view('classeurs.individus_independant.edit', compact('individu', 'dossier', 'classeur', 'themesDisponibles'));
    }

    public function update(Request $request, Classeur $classeur)
    {
        $request->validate([
            'theme' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $classeur->update([
            'theme' => $request->theme,
            'description' => $request->description,
        ]);

        $existant = Classeur::where('dossier_id', $classeur->dossier_id)
            ->where('theme', $request->theme)
            ->where('id', '!=', $classeur->id)
            ->first();
            
        if ($existant) {
            return redirect()->back()
                ->withErrors(['theme' => 'Ce theme existe deja dans votre dossier.'])
                ->withInput();
        }

        return redirect()->route('individu.classeurs.index')
            ->with('success', 'Classeur mis a jour avec succes.');
    }

    private function getThemesDisponibles()
    {
        return [
            'Etat civil' => 'Actes de naissance, mariage, deces...',
            'Identite' => 'CNI, passeport, permis...',
            'Sante' => 'Carnets de vaccination, certificats...',
            'Scolarite' => 'Diplomes, bulletins, attestations...',
            'Foncier' => 'Titres de propriete, contrats de bail...',
            'Justice' => 'Extraits de casier, jugements...',
            'Travail' => 'Contrats, fiches de paie...',
            'Finances' => 'Releves bancaires, attestations...',
            'Assurance' => 'Polices, attestations...',
            'Autre' => 'Autres documents administratifs'
        ];
    }
}
