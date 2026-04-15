<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeQuartier extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelle',
    ];
    
    public function quartiers()
    {
        return $this->hasMany(Quartier::class);
    }
}
