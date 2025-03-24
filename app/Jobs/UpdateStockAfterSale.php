<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateStockAfterSale implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $produitId;
    protected $quantite;

    public function __construct($produitId, $quantite)
    {
        $this->produitId = $produitId;
        $this->quantite = $quantite;
    }

    public function handle()
    {
        $produit = Produit::findOrFail($this->produitId);
        $stock = $produit->stock;

        // Mise à jour du stock
        $stock->quantite -= $this->quantite;
        $stock->save();

        // Mise à jour du nombre de ventes
        $produit->nb_ventes += $this->quantite;
        $produit->save();

        // Vérification pour l'alerte de stock
        if ($stock->quantite <= $stock->seuil_alerte) {
            Alerte::create([
                'produit_id' => $this->produitId,
                'message' => "Le stock du produit {$produit->nom} est bas ({$stock->quantite} restants)",
            ]);
        }
    }
}
