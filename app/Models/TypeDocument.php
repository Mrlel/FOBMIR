<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeDocument extends Model
{
    protected $fillable = [
        'libelle',
    ];

    /**
     * Tous les documents associés à ce type.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
}

