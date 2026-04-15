<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\TypeDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::with(['typeDocument', 'user'])->paginate(10);
        return view('documents.index', compact('documents'));
    }

    public function create()
    {
        $types = TypeDocument::all();
        return view('documents.create', compact('types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'libelle' => 'required|string|max:150',
            'numero' => 'nullable|string|max:25',
            'fichier' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'type_document_id' => 'required|exists:type_documents,id'
        ]);

        $data = $request->except('fichier');
        $data['user_id'] = auth()->id();

        if ($request->hasFile('fichier')) {
            $data['fichier'] = $request->file('fichier')->store('documents', 'public');
        }

        Document::create($data);

        return redirect()->back()->with('success', 'Document créé avec succès.');
    }

    public function show(Document $document)
    {
        return view('documents.show', compact('document'));
    }

    public function edit(Document $document)
    {
        $types = TypeDocument::all();
        return view('documents.edit', compact('document', 'types'));
    }

    public function update(Request $request, Document $document)
    {
        $request->validate([
            'libelle' => 'required|string|max:150',
            'numero' => 'nullable|string|max:25',
            'fichier' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'type_document_id' => 'required|exists:type_documents,id'
        ]);

        $data = $request->except('fichier');

        if ($request->hasFile('fichier')) {
            // Supprimer l'ancien fichier
            if ($document->fichier) {
                Storage::disk('public')->delete($document->fichier);
            }
            $data['fichier'] = $request->file('fichier')->store('documents', 'public');
        }

        $document->update($data);

        return redirect()->route('documents.index')
            ->with('success', 'Document modifié avec succès.');
    }

    public function destroy(Document $document)
    {
        if ($document->fichier) {
            Storage::disk('public')->delete($document->fichier);
        }
        $document->delete();

        return redirect()->route('documents.index')
            ->with('success', 'Document supprimé avec succès.');
    }

    public function download(Document $document)
    {
        return Storage::disk('public')->download($document->fichier);
    }
}