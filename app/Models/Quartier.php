<?php
// app/Models/Quartier.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quartier extends Model
{
    protected $fillable = [
        'nom', 'historique', 'village_id', 'type_quartier_id', 'user_id'
    ];

    public function village()
    {
        return $this->belongsTo(Village::class);
    }

    public function typeQuartier()
    {
        return $this->belongsTo(TypeQuartier::class);
    }

    public function sousQuartiers()
    {
        return $this->hasMany(SousQuartier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
