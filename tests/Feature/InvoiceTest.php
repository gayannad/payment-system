<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_invoices(): void
    {
        $response = $this->getJson('/api/invoices');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'message',
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Invoices retrieved successfully.',
            ]);

        $this->assertIsArray($response->json('data'));
    }
}
