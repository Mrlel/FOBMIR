<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrigineMenage extends Model
{
    protected $fillable = ['libelle'];

    public function menages()
    {
        return $this->hasMany(Menage::class,'origine_id');
    }
}

