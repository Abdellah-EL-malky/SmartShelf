<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['nom', 'code_barre', 'rayon_id', 'description', 'prix', 'image_url', 'populaire', 'actif'];

    public function rayon(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Rayon::class);
    }

    public function stock()
    {
        return $this->hasOne(Stock::class);
    }

    public function promotions()
    {
        return $this->belongsToMany(Promotion::class);
    }

    public function ventes()
    {
        return $this->hasMany(Vente::class);
    }

    // Scope pour les produits populaires
    public function scopePopulaire($query)
    {
        return $query->where('populaire', true);
    }

    // Scope pour les produits en promotion
    public function scopeEnPromotion($query)
    {
        return $query->whereHas('promotions', function($q) {
            $q->where('date_debut', '<=', now())
                ->where('date_fin', '>=', now());
        });
    }
}
