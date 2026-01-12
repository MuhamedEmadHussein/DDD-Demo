<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domains\Order\Aggregates\Order;
use App\Domains\Order\Entities\OrderItem;
use App\Domains\Order\Repositories\OrderRepositoryInterface;
use App\Domains\Order\ValueObjects\OrderStatus;
use App\Domains\Order\ValueObjects\Price;
use Illuminate\Support\Facades\DB;

class EloquentOrderRepository implements OrderRepositoryInterface
{
    public function findById(string $id): ?Order
    {
        $orderModel = OrderModel::with('items')->find($id);

        if (!$orderModel) {
            return null;
        }

        $order = new Order(
            $orderModel->id,
            $orderModel->customer_id,
            OrderStatus::from($orderModel->status)
        );

        foreach ($orderModel->items as $itemModel) {
            $order->addItem(new OrderItem(
                $itemModel->id,
                $itemModel->product_id,
                $itemModel->product_name,
                $itemModel->quantity,
                new Price($itemModel->unit_price, $itemModel->currency)
            ));
        }

        return $order;
    }

    public function save(Order $order): void
    {
        DB::transaction(function () use ($order) {
            $orderModel = OrderModel::updateOrCreate(
                ['id' => $order->getId()],
                [
                    'customer_id' => $order->getCustomerId(),
                    'status' => $order->getStatus()->value,
                    'total_amount' => $order->getTotalPrice()->amount,
                    'currency' => $order->getTotalPrice()->currency,
                ]
            );

            // Simple way: sync items by deleting and re-inserting or matching IDs
            // For this demo, let's delete existing items and re-insert
            $orderModel->items()->delete();

            foreach ($order->getItems() as $item) {
                $orderModel->items()->create([
                    'id' => $item->getId(),
                    'product_id' => $item->getProductId(),
                    'product_name' => $item->getProductName(),
                    'quantity' => $item->getQuantity(),
                    'unit_price' => $item->getUnitPrice()->amount,
                    'currency' => $item->getUnitPrice()->currency,
                ]);
            }
        });
    }

    public function findAll(): array
    {
        return OrderModel::with('items')->get()->map(function ($orderModel) {
            $order = new Order(
                $orderModel->id,
                $orderModel->customer_id,
                OrderStatus::from($orderModel->status)
            );

            foreach ($orderModel->items as $itemModel) {
                $order->addItem(new OrderItem(
                    $itemModel->id,
                    $itemModel->product_id,
                    $itemModel->product_name,
                    $itemModel->quantity,
                    new Price($itemModel->unit_price, $itemModel->currency)
                ));
            }

            return $order;
        })->toArray();
    }
}
