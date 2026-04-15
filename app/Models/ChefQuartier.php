<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChefQuartier extends Model
{
    use HasFactory;
    protected $table = 'chefs_quartier';
    
    protected $casts = [
        'debut_mandat' => 'datetime:Y-m-d',
        'fin_mandat' => 'datetime:Y-m-d',
    ];

    protected $fillable = [
        'nom',
        'sexe',
        'debut_mandat',
        'fin_mandat',
        'quartier_id',
    ];
    public function quartier()
    {
        return $this->belongsTo(Quartier::class);
    }
}
