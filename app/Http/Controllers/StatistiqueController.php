<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Stock;
use App\Models\Alerte;
use Illuminate\Http\Request;

class StatistiqueController extends Controller
{
    public function produitsPopulaires()
    {
        $produits = Produit::orderBy('nb_ventes', 'desc')
            ->with('rayon', 'stock')
            ->take(10)
            ->get();

        return response()->json(['data' => $produits]);
    }

    public function stocksCritiques()
    {
        $stocks = Stock::whereRaw('quantite <= seuil_alerte')
            ->with('produit.rayon')
            ->get();

        return response()->json(['data' => $stocks]);
    }

    public function alertes(Request $request)
    {
        $query = Alerte::query();

        if ($request->has('lu')) {
            $query->where('lu', $request->lu);
        }

        $alertes = $query->with('produit')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['data' => $alertes]);
    }

    public function marquerAlerteLue(Alerte $alerte)
    {
        $alerte->update(['lu' => true]);
        return response()->json(['message' => 'Alerte marquée comme lue']);
    }

    public function rapportVentes(Request $request)
    {
        $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
        ]);

        // Cette méthode nécessiterait une table supplémentaire pour les ventes
        // Mais voici un exemple de ce que vous pourriez faire

        return response()->json([
            'message' => 'Cette fonctionnalité nécessite une table de ventes séparée.'
        ]);
    }
}
