<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rayon;

class RayonController extends Controller
{
    public function index()
    {
        $rayons = Rayon::all();
        return response()->json(['data' => $rayons]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $rayon = Rayon::create($request->all());
        return response()->json(['data' => $rayon], 201);
    }

    public function show(Rayon $rayon)
    {
        return response()->json(['data' => $rayon]);
    }

    public function update(Request $request, Rayon $rayon)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $rayon->update($request->all());
        return response()->json(['data' => $rayon]);
    }

    public function destroy(Rayon $rayon)
    {
        $rayon->delete();
        return response()->json(null, 204);
    }

}
