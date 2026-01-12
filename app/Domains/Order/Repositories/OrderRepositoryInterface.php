<?php

namespace App\Domains\Order\Repositories;

use App\Domains\Order\Aggregates\Order;

interface OrderRepositoryInterface
{
    public function findById(string $id): ?Order;
    public function save(Order $order): void;
    /** @return Order[] */
    public function findAll(): array;
}
