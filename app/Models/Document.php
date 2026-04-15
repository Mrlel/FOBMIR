<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\TypeDocument;
use App\Models\User;
use App\Models\Classeur;
use App\Models\Menage;
use App\Models\IndividusMenage;

class Document extends Model
{
    protected $fillable = [
        'libelle',
        'numero',
        'nom_fichier',
        'fichier',
        'est_valider',
        'remarque',
        'type_document_id',
        'user_id',
        'classeur_id',
        'menage_id',
        'individu_menage_id',
        'individu_independant_id',
        'date_ajout',
    ];

    protected $casts = [
        'date_ajout' => 'datetime',
    ];

    public function typeDocument(): BelongsTo
    {
        return $this->belongsTo(TypeDocument::class);
    }

    /**
     * Auteur du dépôt (point focal ou admin).
     */
    public function auteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Classeur auquel le document appartient.
     */
    public function classeur(): BelongsTo
    {
        return $this->belongsTo(Classeur::class);
    }

    /**
     * Ménage concerné par le document.
     */
    public function menage(): BelongsTo
    {
        return $this->belongsTo(Menage::class);
    }

    /**
     * Individu concerné (facultatif).
     */
    public function individuMenage(): BelongsTo
    {
        return $this->belongsTo(IndividusMenage::class, 'individu_menage_id');
    }
    public function individuIndependant(): BelongsTo
    {
        return $this->belongsTo(IndividuIndependant::class, 'individu_independant_id');
    }
}
