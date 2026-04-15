<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    use HasFactory;
    protected $fillable = [
        'region_id', 'code', 'nom', 'superficie', 'nb_villages'
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function sousPrefectures()
    {
        return $this->hasMany(SousPrefecture::class);
    }
}
