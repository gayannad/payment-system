<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_payments()
    {
        $response = $this->getJson('/api/payments');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'current_page',
                'data' => [
                    '*' => [
                        'id',
                        'customer_name',
                        'amount',
                        'usd_amount',
                        'currency',
                        'payment_date',
                        'is_processed',
                        'created_at',
                    ],
                ],
                'first_page_url',
                'from',
                'last_page',
                'last_page_url',
                'links' => [
                    '*' => [
                        'url',
                        'label',
                        'page',
                        'active',
                    ],
                ],
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to',
                'total',
            ],
        ])
            ->assertJson([
                'success' => true,
                'message' => 'Payments retrieved successfully.',
            ]);

        $this->assertIsInt($response->json('data.current_page'));
        $this->assertIsInt($response->json('data.total'));
        $this->assertIsArray($response->json('data.data'));
    }
}
