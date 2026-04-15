<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SousPrefecture extends Model
{
    use HasFactory;
    protected $fillable = [
        'departement_id', 'code', 'nom', 'superficie', 'nb_villages'
    ];

    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }

    public function communes()
    {
        return $this->hasMany(Commune::class);
    }

    public function villages()
    {
        return $this->hasMany(Village::class);
    }
}
