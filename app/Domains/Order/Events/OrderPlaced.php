<?php

namespace App\Domains\Order\Events;

use App\Domains\Order\Aggregates\Order;

class OrderPlaced
{
    public function __construct(public Order $order) {}
}
