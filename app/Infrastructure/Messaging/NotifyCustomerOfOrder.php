<?php

namespace App\Infrastructure\Messaging;

use App\Domains\Order\Events\OrderPlaced;
use Illuminate\Support\Facades\Log;

class NotifyCustomerOfOrder
{
    public function handle(OrderPlaced $event): void
    {
        // In a real app, this would send an email or push notification
        Log::info("Order placed: " . $event->order->getId() . " for customer: " . $event->order->getCustomerId());
    }
}
