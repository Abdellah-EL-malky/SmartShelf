<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;
    protected $fillable = ['nom', 'description', 'prix', 'rayon_id', 'categorie', 'en_promotion', 'nb_ventes'];

    public function rayon()
    {
        return $this->belongsTo(Rayon::class);
    }

    public function stock()
    {
        return $this->hasOne(Stock::class);
    }

    public function alertes()
    {
        return $this->hasMany(Alerte::class);
    }
}
