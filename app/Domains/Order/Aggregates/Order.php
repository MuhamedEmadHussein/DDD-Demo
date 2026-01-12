<?php

namespace App\Domains\Order\Aggregates;

use App\Domains\Order\Entities\OrderItem;
use App\Domains\Order\ValueObjects\OrderStatus;
use App\Domains\Order\ValueObjects\Price;
use App\Domains\Order\Events\OrderPlaced;
use App\Domains\Order\Events\OrderCancelled;
use Exception;

class Order
{
    /** @var OrderItem[] */
    private array $items = [];
    private OrderStatus $status;
    private array $recordedEvents = [];

    public function __construct(
        private readonly string $id,
        private readonly string $customerId,
        OrderStatus $status = OrderStatus::PENDING
    ) {
        $this->status = $status;
    }

    public static function place(string $id, string $customerId, array $items): self
    {
        $order = new self($id, $customerId);
        foreach ($items as $item) {
            $order->addItem($item);
        }
        
        $order->recordEvent(new OrderPlaced($order));
        
        return $order;
    }

    public function addItem(OrderItem $item): void
    {
        $this->items[] = $item;
    }

    public function cancel(): void
    {
        if (!$this->status->canBeCancelled()) {
            throw new Exception("Order cannot be cancelled in its current state.");
        }

        $this->status = OrderStatus::CANCELLED;
        $this->recordEvent(new OrderCancelled($this));
    }

    public function getTotalPrice(): Price
    {
        $total = new Price(0);
        foreach ($this->items as $item) {
            $total = $total->add($item->getTotalPrice());
        }
        return $total;
    }

    public function getId(): string { return $this->id; }
    public function getCustomerId(): string { return $this->customerId; }
    public function getStatus(): OrderStatus { return $this->status; }
    public function getItems(): array { return $this->items; }

    private function recordEvent($event): void
    {
        $this->recordedEvents[] = $event;
    }

    public function pullEvents(): array
    {
        $events = $this->recordedEvents;
        $this->recordedEvents = [];
        return $events;
    }
}
