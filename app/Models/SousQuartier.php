<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SousQuartier extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'historique',
        'quartier_id',
        'type_sous_quartier_id',
    ];

    public function quartier()
    {
        return $this->belongsTo(Quartier::class);
    }

    public function typeSousQuartier()
    {
        return $this->belongsTo(TypeSousQuartier::class);
    }
    
    public function menages()
    {
        return $this->hasMany(Menage::class);
    }
}
