<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
    use HasFactory;
    protected $fillable = [
        'sous_prefecture_id', 'code', 'nom'
    ];

    public function sousPrefecture()
    {
        return $this->belongsTo(SousPrefecture::class);
    }

    public function villages()
    {
        return $this->hasMany(Village::class);
    }
}
