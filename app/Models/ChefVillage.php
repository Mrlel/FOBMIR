<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChefVillage extends Model
{
    use HasFactory;
    protected $table = 'chefs_village';
    
    protected $fillable = [
        'nom',
        'sexe',
        'village_id',
        'debut_mandat',
        'fin_mandat',
        'num_arrete_nomination',
    ];
    protected $casts = [
        'debut_mandat' => 'datetime:Y-m-d',
        'fin_mandat' => 'datetime:Y-m-d',
    ];
    
    public function village()
    {
        return $this->belongsTo(Village::class);
    }
}
