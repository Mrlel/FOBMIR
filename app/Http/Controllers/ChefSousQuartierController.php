<?php

namespace App\Http\Controllers;

use App\Models\ChefSousQuartier;
use App\Models\SousQuartier;
use Illuminate\Http\Request;

class ChefSousQuartierController extends Controller
{

    public function index()
    {
        $chefs = ChefSousQuartier::with('sousQuartier')->paginate(10);
        return view('chefs-sous-quartier.index', compact('chefs'));
    }

    public function create()
    {
        $sousQuartiers = SousQuartier::all();
        return view('chefs-sous-quartier.create', compact('sousQuartiers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:150',
            'sexe' => 'required|in:M,F',
            'debut_mandat' => 'nullable|date',
            'fin_mandat' => 'nullable|date|after:debut_mandat',
            'sous_quartier_id' => 'required|exists:sous_quartiers,id'
        ]);

        ChefSousQuartier::create($request->all());

        return redirect()->route('chefs-sous-quartier.index')
            ->with('success', 'Chef de sous-quartier créé avec succès.');
    }

    public function show(ChefSousQuartier $chefSousQuartier)
    {
        return view('chefs-sous-quartier.show', compact('chefSousQuartier'));
    }

    public function edit(ChefSousQuartier $chefSousQuartier)
    {
        $sousQuartiers = SousQuartier::all();
        return view('chefs-sous-quartier.edit', compact('chefSousQuartier', 'sousQuartiers'));
    }

    public function update(Request $request, ChefSousQuartier $chefSousQuartier)
    {
        $request->validate([
            'nom' => 'required|string|max:150',
            'sexe' => 'required|in:M,F',
            'debut_mandat' => 'nullable|date',
            'fin_mandat' => 'nullable|date|after:debut_mandat',
            'sous_quartier_id' => 'required|exists:sous_quartiers,id'
        ]);

        $chefSousQuartier->update($request->all());

        return redirect()->route('chefs-sous-quartier.index')
            ->with('success', 'Chef de sous-quartier modifié avec succès.');
    }

    public function destroy(ChefSousQuartier $chefSousQuartier)
    {
        $chefSousQuartier->delete();

        return redirect()->route('chefs-sous-quartier.index')
            ->with('success', 'Chef de sous-quartier supprimé avec succès.');
    }
}