<?php

namespace App\Http\Controllers;

use App\Models\ChefQuartier;
use App\Models\Quartier;
use Illuminate\Http\Request;

class ChefQuartierController extends Controller
{
    public function index()
    {
        $chefs = ChefQuartier::with('quartier')->paginate(10);
        $quartiers = Quartier::all();
        return view('gestion_chefs.quartiers.index', compact('chefs', 'quartiers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:150',
            'sexe' => 'required|in:M,F',
            'debut_mandat' => 'nullable|date',
            'fin_mandat' => 'nullable|date|after:debut_mandat',
            'quartier_id' => 'required|exists:quartiers,id'
        ]);

        ChefQuartier::create($request->all());

        return redirect()->route('gestion_chefs.quartiers.index')
            ->with('success', 'Chef de quartier créé avec succès.');
    }

    public function show(ChefQuartier $chefQuartier)
    {
        return view('gestion_chefs.quartiers.show', compact('chefQuartier'));
    }

    public function edit(ChefQuartier $chefQuartier)
    {
        $quartiers = Quartier::all();
        return view('gestion_chefs.quartiers.edit', compact('chefQuartier', 'quartiers'));
    }

    public function update(Request $request, ChefQuartier $chefQuartier)
    {
        $request->validate([
            'nom' => 'required|string|max:150',
            'sexe' => 'required|in:M,F',
            'debut_mandat' => 'nullable|date',
            'fin_mandat' => 'nullable|date|after:debut_mandat',
            'quartier_id' => 'required|exists:quartiers,id'
        ]);

        $chefQuartier->update($request->all());

        return redirect()->route('gestion_chefs.quartiers.index')
            ->with('success', 'Chef de quartier modifié avec succès.');
    }

    public function destroy(ChefQuartier $chefQuartier)
    {
        $chefQuartier->delete();

        return redirect()->route('gestion_chefs.quartiers.index')
            ->with('success', 'Chef de quartier supprimé avec succès.');
    }
}