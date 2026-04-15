<?php

namespace App\Http\Controllers;

use App\Models\ChefVillage;
use App\Models\Village;
use Illuminate\Http\Request;

class ChefVillageController extends Controller
{
    public function index()
    {
        $chefs = ChefVillage::with('village')->paginate(10);
        return view('gestion_chefs.villages.index', compact('chefs'));
    }

    public function create()
    {
        $villages = Village::all();
        return view('gestion_chefs.villages.create', compact('villages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:150',
            'sexe' => 'required|in:M,F',
            'debut_mandat' => 'nullable|date',
            'fin_mandat' => 'nullable|date|after:debut_mandat',
            'num_arrete_nomination' => 'nullable|string|max:25',
            'village_id' => 'required|exists:villages,id'
        ]);

        ChefVillage::create($request->all());
        return redirect()->back()
            ->with('success', 'Chef de village créé avec succès.');
    }

    public function show(ChefVillage $chefVillage)
    {
        return view('gestion_chefs.villages.show', compact('chefVillage'));
    }

    public function edit(ChefVillage $chefVillage)
    {
        $villages = Village::all();
        return view('gestion_chefs.villages.edit', compact('chefVillage', 'villages'));
    }

    public function update(Request $request, ChefVillage $chefVillage)
    {
        $request->validate([
            'nom' => 'required|string|max:150',
            'sexe' => 'required|in:M,F',
            'debut_mandat' => 'nullable|date',
            'fin_mandat' => 'nullable|date|after:debut_mandat',
            'num_arrete_nomination' => 'nullable|string|max:25',
            'village_id' => 'required|exists:villages,id'
        ]);

        $chefVillage->update($request->all());

        return redirect()->back()
            ->with('success', 'Chef de village modifié avec succès.');
    }

    public function destroy(ChefVillage $chefVillage)
    {
        $chefVillage->delete();

        return redirect()->back()
            ->with('success', 'Chef de village supprimé avec succès.');
    }
}