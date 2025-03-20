<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = ['produit_id', 'quantite', 'seuil_alerte'];
    private mixed $quantite;
    private mixed $seuil_alerte;

    public function produit(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Produit::class);
    }

    public function estEnAlerte(): bool
    {
        return $this->quantite <= $this->seuil_alerte;
    }
}
