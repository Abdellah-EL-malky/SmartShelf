<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vente extends Model
{
    use HasFactory;

    protected $fillable = ['produit_id', 'quantite', 'prix_unitaire'];

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
}
