<?php

namespace App\Domains\Order\Entities;

use App\Domains\Order\ValueObjects\Price;

class OrderItem
{
    public function __construct(
        private readonly string $id,
        private readonly string $productId,
        private readonly string $productName,
        private readonly int $quantity,
        private readonly Price $unitPrice
    ) {}

    public function getId(): string { return $this->id; }
    public function getProductId(): string { return $this->productId; }
    public function getProductName(): string { return $this->productName; }
    public function getQuantity(): int { return $this->quantity; }
    public function getUnitPrice(): Price { return $this->unitPrice; }

    public function getTotalPrice(): Price
    {
        return new Price($this->unitPrice->amount * $this->quantity, $this->unitPrice->currency);
    }
}
