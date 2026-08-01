<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChefSousQuartier extends Model
{
    use HasFactory;

    protected $table = 'chefs_sous_quartier';

    protected $fillable = [
        'nom',
        'sexe',
        'debut_mandat',
        'fin_mandat',
        'sous_quartier_id',
    ];

    protected $casts = [
        'debut_mandat' => 'date:Y-m-d',
        'fin_mandat' => 'date:Y-m-d',
    ];

    public function sousQuartier()
    {
        return $this->belongsTo(SousQuartier::class);
    }
}
