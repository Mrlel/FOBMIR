<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pays extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'nom',
        'capitale_politique',
        'capitale_economique',
        'superficie',
        'continent',
        'indicatif_tel',
    ];

    public function districts()
    {
        return $this->hasMany(District::class);
    }
}
