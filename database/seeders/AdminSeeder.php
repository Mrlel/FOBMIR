<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{

    public function run(): void
    {
        $nom = env('ADMIN_NOM');
        $prenom = env('ADMIN_PRENOM');
        $telephone = env('ADMIN_TELEPHONE');

        if (! $nom || ! $prenom || ! $telephone) {
            $this->command?->warn(
                'AdminSeeder ignoré : définissez ADMIN_NOM, ADMIN_PRENOM et ADMIN_TELEPHONE dans .env pour créer le compte superadmin initial.'
            );
            return;
        }

        $password = env('ADMIN_PASSWORD') ?: Str::password(16);

        User::create([
            'nom' => $nom,
            'prenom' => $prenom,
            'telephone' => $telephone,
            'role' => 'superadmin',
            'password' => Hash::make($password),
            'must_change_password' => true,
        ]);

        if (! env('ADMIN_PASSWORD')) {
            $this->command?->info("Compte superadmin créé. Mot de passe temporaire généré : {$password}");
        }
    }
}
