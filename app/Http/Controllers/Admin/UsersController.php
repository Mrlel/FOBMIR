<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'nom'        => 'required|string|max:255',
            'prenom'     => 'required|string|max:255',
            'telephone'  => 'required|string|max:20',
            'role'       => 'required|in:superadmin,admin,point_focal',
            'village_id' => 'required|integer|exists:villages,id',
        ]);

        User::create([
            'nom'        => $data['nom'],
            'prenom'     => $data['prenom'],
            'telephone'  => $data['telephone'],
            'role'       => $data['role'],
            'village_id' => $data['village_id'],
            'password'   => Hash::make('admin1234'),
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'Utilisateur enregistré avec succès.');
    }

    
}
