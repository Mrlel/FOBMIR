<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'chef_lieu',
        'superficie',
        'nom',
        'pays_id',
        'nb_villages',
    ];

    public function pays()
    {
        return $this->belongsTo(Pays::class);
    }

    public function regions()
    {
        return $this->hasMany(Region::class);
    }
}
