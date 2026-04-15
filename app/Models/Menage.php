<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menage extends Model
{
    protected $fillable = ['nom_chef','nb_individus','nb_indiv_prst','nb_indiv_abs','sexe_chef','origine_id','sous_quartier_id'];

    public function origine()
    {
        return $this->belongsTo(OrigineMenage::class,'origine_id');
    }

    public function sousQuartier()
    {
        return $this->belongsTo(SousQuartier::class);
    }

    public function quartier()
    {
        return $this->sousQuartier?->quartier();
    }

    public function village()
    {
        return $this->sousQuartier?->quartier?->village();
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function pochette()
    {
        return $this->hasOne(Pochette::class);
    }

    public function classeurs()
    {
        return $this->hasManyThrough(Classeur::class, Pochette::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Individus du ménage
     */
    public function individus()
    {
        return $this->hasMany(IndividusMenage::class);
    }
}

