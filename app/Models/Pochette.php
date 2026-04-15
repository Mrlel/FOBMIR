<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pochette extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelle',
        'description',
        'menage_id',
    ];

    public function menage()
    {
        return $this->belongsTo(Menage::class);
    }

    public function classeurs()
    {
        return $this->hasMany(Classeur::class);
    }

    /**
     * Dossiers individuels dans cette pochette
     */
    public function dossiers()
    {
        return $this->hasMany(Dossier::class);
    }

    /**
     * Classeurs du ménage (directement liés à la pochette)
     */
    public function classeursMenuage()
    {
        return $this->hasMany(Classeur::class)->whereNull('dossier_id');
    }

    /**
     * Tous les documents de la pochette (ménage + individuels)
     */
    public function tousLesDocuments()
    {
        return $this->hasManyThrough(Document::class, Classeur::class);
    }
}
