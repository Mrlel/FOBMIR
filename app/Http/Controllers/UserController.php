<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Menage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with([
            'village.sousPrefecture.departement.region.district.pays',
        ])->paginate(10);
        return view('Admin.utilisateurs.index', compact('users'));
    }

    public function create()
    {
        return view('Admin.utilisateurs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:150',
            'telephone' => 'required|string|unique:users',
            'role' => 'required|in:superadmin,admin,point_focal',
            'village_id' => 'required|exists:villages,id',
        ]);

        // Seul un superadmin peut créer un autre compte superadmin (anti-élévation de privilège).
        if ($request->role === 'superadmin' && Auth::user()->role !== 'superadmin') {
            return redirect()->back()->with('error', 'Seul un super administrateur peut créer un compte super administrateur.');
        }

        $data = $request->only(['nom', 'prenom', 'telephone', 'role', 'village_id']);

        // Mot de passe temporaire aléatoire : jamais de mot de passe fixe/prévisible.
        // L'utilisateur devra le changer dès sa première connexion (must_change_password).
        $temporaryPassword = Str::password(14);
        $data['password'] = Hash::make($temporaryPassword);
        $data['must_change_password'] = true;

        User::create($data);

        return redirect()->back()
            ->with('success', 'Utilisateur créé avec succès.')
            ->with('generated_password', $temporaryPassword);
    }

    public function show(User $user)
    {
        $user->load([
            'village.sousPrefecture.departement.region.district.pays',
            'village.commune'
        ]);
        
        return view('Admin.utilisateurs.show', compact('user'));
    }

    public function edit(User $user)
    {
        $user->load(['village.sousPrefecture.departement.region.district.pays', 'village.commune']);

        $chain = [
            'pays_id' => $user->village?->sousPrefecture?->departement?->region?->district?->pays?->id,
            'district_id' => $user->village?->sousPrefecture?->departement?->region?->district?->id,
            'region_id' => $user->village?->sousPrefecture?->departement?->region?->id,
            'departement_id' => $user->village?->sousPrefecture?->departement?->id,
            'sous_prefecture_id' => $user->village?->sousPrefecture?->id,
            'commune_id' => $user->village?->commune?->id,
            'village_id' => $user->village_id,
        ];

        return view('Admin.utilisateurs.edit', compact('user', 'chain'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:150',
            'telephone' => 'required|string|unique:users,telephone,' . $user->id,
            'role' => 'required|in:superadmin,admin,point_focal',
            'village_id' => 'required|exists:villages,id',
            'password' => 'nullable|string|min:8',
        ]);

        // Seul un superadmin peut promouvoir un utilisateur au rôle superadmin.
        if ($request->role === 'superadmin' && $user->role !== 'superadmin' && Auth::user()->role !== 'superadmin') {
            return redirect()->back()->with('error', 'Seul un super administrateur peut attribuer le rôle super administrateur.');
        }

        $data = $request->only(['nom', 'prenom', 'telephone', 'role', 'village_id']);

        if ($request->filled('password')) {
            // Un mot de passe réinitialisé par un administrateur doit être changé
            // par l'utilisateur concerné à sa prochaine connexion.
            $data['password'] = Hash::make($request->password);
            $data['must_change_password'] = true;
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Utilisateur modifié avec succès.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->back()->with('success', 'Utilisateur supprimé avec succès.');
    }
}