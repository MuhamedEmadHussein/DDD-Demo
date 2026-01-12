<?php

namespace App\Domains\Order\Events;

use App\Domains\Order\Aggregates\Order;

class OrderCancelled
{
    public function __construct(public Order $order) {}
}
