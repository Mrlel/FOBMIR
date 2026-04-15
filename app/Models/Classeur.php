<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classeur extends Model
{
    use HasFactory;

    protected $fillable = [
        'theme',
        'description',
        'pochette_id',
        'dossier_id',
    ];

    public function pochette()
    {
        return $this->belongsTo(Pochette::class);
    }

    public function dossier()
    {
        return $this->belongsTo(Dossier::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Détermine si ce classeur appartient à un dossier individuel ou à la pochette du ménage
     */
    public function isIndividuel()
    {
        return $this->dossier_id !== null;
    }

    /**
     * Détermine si ce classeur appartient à la pochette du ménage
     */
    public function isMenage()
    {
        return $this->dossier_id === null;
    }

    /**
     * Récupère le ménage via la pochette ou le dossier
     */
    public function getMenageAttribute()
    {
        if ($this->dossier_id) {
            return $this->dossier->pochette->menage;
        }
        return $this->pochette->menage;
    }
}
