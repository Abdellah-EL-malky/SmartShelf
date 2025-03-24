<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('description')->nullable();
            $table->decimal('prix', 8, 2);
            $table->unsignedBigInteger('rayon_id');
            $table->string('categorie');
            $table->boolean('en_promotion')->default(false);
            $table->integer('nb_ventes')->default(0); // Pour les produits populaires
            $table->timestamps();
            $table->foreign('rayon_id')->references('id')->on('rayons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
