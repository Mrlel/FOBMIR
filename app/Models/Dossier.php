<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dossier extends Model
{
    use HasFactory;

    protected $fillable = [
        'pochette_id',
        'individu_menage_id',
        'individu_independant_id',
        'nom',
        'description',
    ];

    /**
     * Pochette parente
     */
    public function pochette()
    {
        return $this->belongsTo(Pochette::class);
    }
   public function individuIndependant()
    {
        return $this->belongsTo(IndividuIndependant::class);
    }
    /**
     * Individu propriétaire du dossier
     */
    public function individuMenage()
    {
        return $this->belongsTo(IndividusMenage::class, 'individu_menage_id');
    }

    /**
     * Classeurs contenus dans ce dossier
     */
    public function classeurs()
    {
        return $this->hasMany(Classeur::class);
    }

    /**
     * Tous les documents de ce dossier (via les classeurs)
     */
    public function documents()
    {
        return $this->hasManyThrough(Document::class, Classeur::class);
    }

    /**
     * Ménage via la pochette
     */
    public function menage()
    {
        return $this->pochette->menage();
    }
}