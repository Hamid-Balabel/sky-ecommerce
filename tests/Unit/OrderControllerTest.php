<?php

namespace Tests\Unit;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use PHPUnit\Framework\TestCase;

class OrderControllerTest extends TestCase
{
    /**
     * Test status update validation.
     */
    public function test_change_status_validation(): void
    {
        $this->assertTrue(OrderStatusEnum::tryFrom('pending') instanceof OrderStatusEnum);
        $this->assertNull(OrderStatusEnum::tryFrom('invalid-status'));
    }

    /**
     * Test order total calculation logic.
     */
    public function test_order_total_calculation(): void
    {
        $products = [
            ['price' => 100, 'quantity' => 2],
            ['price' => 50, 'quantity' => 1],
        ];

        $totalAmount = collect($products)->reduce(function ($carry, $product) {
            return $carry + ($product['price'] * $product['quantity']);
        }, 0);

        $this->assertEquals(250, $totalAmount);
    }

}
