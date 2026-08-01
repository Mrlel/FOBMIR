<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentPayment extends Model
{
    protected $fillable = [
        'document_id',
        'email_acheteur',
        'telephone_acheteur',
        'nom_acheteur',
        'statut',
        'transaction_id',
        'token_telechargement',
        'paye_le',
    ];

    protected $casts = [
        'paye_le' => 'datetime',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function scopePaye($query)
    {
        return $query->where('statut', 'paye');
    }
}
