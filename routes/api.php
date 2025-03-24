<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RayonController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StatistiqueController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/rayons/{rayon}/produits', [ProduitController::class, 'indexByRayon']);
    Route::get('/produits/search', [ProduitController::class, 'search']);
    Route::get('/rayons/{rayon}/produits/populaires', [ProduitController::class, 'populaires']);
    Route::get('/rayons/{rayon}/produits/promotions', [ProduitController::class, 'promotions']);

    // Routes pour les administrateurs (à protéger avec middleware admin)
    Route::middleware('admin')->group(function () {
        Route::apiResource('rayons', RayonController::class);
        Route::apiResource('produits', ProduitController::class);
        Route::apiResource('stocks', StockController::class);
        Route::get('/statistiques/produits-populaires', [StatistiqueController::class, 'produitsPopulaires']);
        Route::get('/statistiques/stocks-critiques', [StatistiqueController::class, 'stocksCritiques']);
        Route::get('/alertes', [StatistiqueController::class, 'alertes']);
    });
});
