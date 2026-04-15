<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class DocumentPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'payer_type',
        'payer_id',
        'provider',
        'status',
        'amount',
        'token',
        'payment_url',
        'numero_send',
        'nomclient',
        'provider_payload',
        'paid_at',
    ];

    protected $casts = [
        'provider_payload' => 'array',
        'paid_at' => 'datetime',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function payer(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }
}

