<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test order creation.
     */
    public function test_order_creation(): void
    {
        $user = User::factory()->create();
        $products = Product::factory()->count(2)->create();

        $payload = [
            'products' => $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'price' => $product->price,
                    'quantity' => 2,
                ];
            })->toArray(),
        ];

        $response = $this->actingAs($user)
            ->postJson(route('orders.store'), $payload);

        $response->assertCreated();

        $this->assertDatabaseHas('orders', [
            'customer_id' => $user->id,
        ]);
    }

    /**
     * Test order status update.
     */
    public function test_change_status(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create();

        $payload = ['status' => 'progress'];

        $response = $this->actingAs($user)->putJson("/api/orders/change-status/{$order->id}", $payload);

        $response->assertOk();
        $this->assertEquals('progress', $order->refresh()->status);
    }


    /**
     * Test force delete order.
     */
    public function test_force_delete_order(): void
    {
        $user = User::factory()->create(['type' => 'admin']);
        $order = Order::factory()->create();
        $order->delete();

        $response = $this->actingAs($user)
            ->deleteJson(route('orders.force-delete', ['id' => $order->id]));

        $response->assertOk();
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }

}
