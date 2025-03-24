<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Rayon;
use App\Models\Stock;
use App\Jobs\UpdateStockAfterSale;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    public function index()
    {
        $produits = Produit::with('rayon', 'stock')->get();
        return response()->json(['data' => $produits]);
    }

    public function indexByRayon(Rayon $rayon)
    {
        $produits = $rayon->produits()->with('stock')->get();
        return response()->json(['data' => $produits]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix' => 'required|numeric|min:0',
            'rayon_id' => 'required|exists:rayons,id',
            'categorie' => 'required|string|max:255',
            'en_promotion' => 'boolean',
        ]);

        $produit = Produit::create($request->all());

        // Création du stock initial
        if ($request->has('quantite_initiale')) {
            Stock::create([
                'produit_id' => $produit->id,
                'quantite' => $request->quantite_initiale,
                'seuil_alerte' => $request->seuil_alerte ?? 5,
            ]);
        }

        return response()->json(['data' => $produit], 201);
    }

    public function show(Produit $produit)
    {
        return response()->json(['data' => $produit->load('rayon', 'stock')]);
    }

    public function update(Request $request, Produit $produit)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix' => 'required|numeric|min:0',
            'rayon_id' => 'required|exists:rayons,id',
            'categorie' => 'required|string|max:255',
            'en_promotion' => 'boolean',
        ]);

        $produit->update($request->all());
        return response()->json(['data' => $produit]);
    }

    public function destroy(Produit $produit)
    {
        $produit->delete();
        return response()->json(null, 204);
    }

    public function search(Request $request)
    {
        $query = Produit::query();

        if ($request->has('q')) {
            $query->where('nom', 'like', '%' . $request->q . '%');
        }

        if ($request->has('categorie')) {
            $query->where('categorie', $request->categorie);
        }

        $produits = $query->with('rayon', 'stock')->get();

        return response()->json(['data' => $produits]);
    }

    public function populaires(Rayon $rayon)
    {
        $produits = $rayon->produits()->orderBy('nb_ventes', 'desc')
            ->with('stock')
            ->take(10)
            ->get();

        return response()->json(['data' => $produits]);
    }

    public function promotions(Rayon $rayon)
    {
        $produits = $rayon->produits()->where('en_promotion', true)
            ->with('stock')
            ->get();

        return response()->json(['data' => $produits]);
    }

    public function enregistrerVente(Request $request, Produit $produit)
    {
        $request->validate([
            'quantite' => 'required|integer|min:1',
        ]);

        // Vérification de la disponibilité
        if ($produit->stock->quantite < $request->quantite) {
            return response()->json(['message' => 'Stock insuffisant'], 400);
        }

        // Dispatch du job pour mettre à jour le stock
        UpdateStockAfterSale::dispatch($produit->id, $request->quantite);

        return response()->json(['message' => 'Vente enregistrée avec succès']);
    }
}
