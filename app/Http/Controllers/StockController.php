<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Produit;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $stocks = Stock::with('produit')->get();
        return response()->json(['data' => $stocks]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'produit_id' => 'required|exists:produits,id|unique:stocks,produit_id',
            'quantite' => 'required|integer|min:0',
            'seuil_alerte' => 'required|integer|min:1',
        ]);

        $stock = Stock::create($request->all());
        return response()->json(['data' => $stock], 201);
    }

    public function show(Stock $stock)
    {
        return response()->json(['data' => $stock->load('produit')]);
    }

    public function update(Request $request, Stock $stock)
    {
        $request->validate([
            'quantite' => 'required|integer|min:0',
            'seuil_alerte' => 'required|integer|min:1',
        ]);

        $stock->update($request->all());

        // Vérification du seuil pour créer une alerte si nécessaire
        if ($stock->quantite <= $stock->seuil_alerte) {
            $produit = $stock->produit;
            $produit->alertes()->create([
                'message' => "Le stock du produit {$produit->nom} est bas ({$stock->quantite} restants)",
            ]);
        }

        return response()->json(['data' => $stock]);
    }

    public function destroy(Stock $stock)
    {
        $stock->delete();
        return response()->json(null, 204);
    }
}
