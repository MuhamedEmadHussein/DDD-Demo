<?php

namespace App\Application\Services;

use App\Domains\Order\Repositories\OrderRepositoryInterface;
use Illuminate\Support\Facades\Event;
use Exception;

class CancelOrderService
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository
    ) {}

    public function execute(string $orderId): void
    {
        $order = $this->orderRepository->findById($orderId);

        if (!$order) {
            throw new Exception("Order not found.");
        }

        $order->cancel();

        $this->orderRepository->save($order);

        foreach ($order->pullEvents() as $event) {
            Event::dispatch($event);
        }
    }
}
