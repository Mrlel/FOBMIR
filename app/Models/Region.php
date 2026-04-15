<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;
    protected $fillable = [
        'district_id', 'code', 'nom', 'chef_lieu', 'superficie', 'nb_villages'
    ];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function departements()
    {
        return $this->hasMany(Departement::class);
    }
}
