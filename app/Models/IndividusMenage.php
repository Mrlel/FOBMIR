<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndividusMenage extends Model
{
    use HasFactory;

    protected $table = 'individu_menage';

    protected $fillable = [
        'nom',
        'prenom',
        'telephone',
        'date_naissance',
        'lieu_naissance',
        'numpiece',
        'num_extrait_naissance',
        'emploi',
        'doc_piece',
        'nom_piece',
        'menage_id',
        'point_focal_id',
    ];

    public function menage()
    {
        return $this->belongsTo(Menage::class);
    }

    public function pointFocal()
    {
        return $this->belongsTo(User::class, 'point_focal_id');
    }

    /**
     * Documents associés à cet individu
     */
    public function documents()
    {
        return $this->hasMany(Document::class, 'individu_menage_id');
    }

    /**
     * Dossier personnel de cet individu
     */
    public function dossier()
    {
        return $this->hasOne(Dossier::class, 'individu_menage_id');
    }

    /**
     * Récupère la géolocalisation complète de l'individu via le point focal
     * Les individus héritent de la géolocalisation du point focal qui les a enregistrés
     */
    public function getGeolocalisationAttribute()
    {
        if (!$this->pointFocal || !$this->pointFocal->village_id) {
            return null;
        }

        $village = $this->pointFocal->village;
        if (!$village) {
            return null;
        }

        return [
            'village' => $village->nom,
            'village_id' => $village->id,
            'sous_prefecture' => $village->sousPrefecture?->nom,
            'sous_prefecture_id' => $village->sous_prefecture_id,
            'departement' => $village->sousPrefecture?->departement?->nom,
            'departement_id' => $village->sousPrefecture?->departement_id,
            'region' => $village->sousPrefecture?->departement?->region?->nom,
            'region_id' => $village->sousPrefecture?->departement?->region_id,
            'district' => $village->sousPrefecture?->departement?->region?->district?->nom,
            'district_id' => $village->sousPrefecture?->departement?->region?->district_id,
            'pays' => $village->sousPrefecture?->departement?->region?->district?->pays?->nom,
            'pays_id' => $village->sousPrefecture?->departement?->region?->district?->pays_id,
            'commune' => $village->commune?->nom,
            'commune_id' => $village->commune_id,
        ];
    }
}
