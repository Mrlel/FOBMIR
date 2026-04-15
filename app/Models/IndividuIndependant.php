<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class IndividuIndependant extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'individus_independants';

    protected $fillable = [
        'prenom',
        'nom',
        'email',
        'password',
        'telephone',
        'date_naissance',
        'sexe',
        'profession',
        'adresse_complete',
        'latitude',
        'longitude',
        'email_verifie',
        'profil_complet',
        'derniere_connexion',
        'verification_token'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_naissance' => 'date',
        'email_verifie' => 'boolean',
        'profil_complet' => 'boolean',
        'actif' => 'boolean',
        'derniere_connexion' => 'datetime',
        'password' => 'hashed',
    ];

    // Relations documents

    public function dossier()
    {
        return $this->hasOne(Dossier::class);
    }

    public function classeurs()
    {
        return $this->hasManyThrough(Classeur::class, Dossier::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    // Méthodes utilitaires
    public function creerStructureDocuments()
    {
        // Créer le dossier
        $dossier = $this->dossier()->create([
            'nom' => 'Dossier personnel de ' . $this->nom,
            'description' => 'Dossier principal pour tous les documents personnels',
            'individu_independant_id' => $this->id,
        ]);

        return $dossier;
    }
}
