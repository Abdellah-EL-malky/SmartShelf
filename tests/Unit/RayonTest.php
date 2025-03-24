<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class RayonTest extends TestCase
{
    public function test_peut_creer_un_rayon()
    {
        $rayonData = [
            'nom' => 'Fruits et Légumes',
            'description' => 'Produits frais'
        ];

        $response = $this->actingAs($this->createAdminUser())
            ->postJson('/api/rayons', $rayonData);

        $response->assertStatus(201)
            ->assertJsonFragment(['nom' => 'Fruits et Légumes']);
    }
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }
}
