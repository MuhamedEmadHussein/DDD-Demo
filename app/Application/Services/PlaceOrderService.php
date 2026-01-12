<?php

namespace App\Application\Services;

use App\Domains\Order\Aggregates\Order;
use App\Domains\Order\Entities\OrderItem;
use App\Domains\Order\Repositories\OrderRepositoryInterface;
use App\Domains\Order\ValueObjects\Price;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Event;

class PlaceOrderService
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository
    ) {}

    public function execute(string $customerId, array $itemsData): Order
    {
        $items = [];
        foreach ($itemsData as $data) {
            $items[] = new OrderItem(
                Str::uuid()->toString(),
                $data['product_id'],
                $data['product_name'],
                $data['quantity'],
                new Price($data['unit_price'], $data['currency'] ?? 'USD')
            );
        }

        $order = Order::place(
            Str::uuid()->toString(),
            $customerId,
            $items
        );

        $this->orderRepository->save($order);

        // Dispatch domain events
        foreach ($order->pullEvents() as $event) {
            Event::dispatch($event);
        }

        return $order;
    }
}
