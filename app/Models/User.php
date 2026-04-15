<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Menage;
use App\Models\Document;
use App\Models\Village;
use App\Models\IndividusMenage;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'telephone',
        'role',
        'password',
        'village_id',
    ];


    public function village()
    {
        return $this->belongsTo(Village::class);
    }
    
    /**
     * Relation avec les individus ménages enregistrés par ce point focal
     */
    public function individusMenages()
    {
        return $this->hasMany(IndividusMenage::class, 'point_focal_id');
    }

    /**
     * Récupère la géolocalisation complète du point focal
     * Retourne un tableau avec toute la chaîne hiérarchique : pays -> district -> région -> département -> sous-préfecture -> commune -> village
     */
    public function getGeolocalisationAttribute()
    {
        if (!$this->village_id || !$this->village) {
            return null;
        }

        $village = $this->village;
        
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
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'date_naissance' => 'date:Y-m-d',
    ];
}
