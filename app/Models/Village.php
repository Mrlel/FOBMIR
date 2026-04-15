<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    use HasFactory;
    protected $fillable = [
        'sous_prefecture_id', 'commune_id', 'code', 'nom', 'secteur_communal', 'num_arrete_creation'
    ];

    public function sousPrefecture()
    {
        return $this->belongsTo(SousPrefecture::class);
    }

    public function commune()
    {
        return $this->belongsTo(Commune::class);
    }

    public function quartiers()
    {
        return $this->hasMany(Quartier::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
    
}
