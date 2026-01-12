<?php

namespace App\Http\Controllers;

use App\Application\Services\PlaceOrderService;
use App\Application\Services\CancelOrderService;
use App\Domains\Order\Repositories\OrderRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function __construct(
        private readonly PlaceOrderService $placeOrderService,
        private readonly CancelOrderService $cancelOrderService,
        private readonly OrderRepositoryInterface $orderRepository
    ) {}

    public function index()
    {
        $orders = $this->orderRepository->findAll();
        
        return view('orders.index', compact('orders'));
    }

    public function store(Request $request): JsonResponse
    {
        $order = $this->placeOrderService->execute(
            $request->input('customer_id', 'CUST-101'),
            $request->input('items', [])
        );

        return response()->json([
            'message' => 'Order placed successfully',
            'order_id' => $order->getId()
        ]);
    }

    public function cancel(string $id): JsonResponse
    {
        try {
            $this->cancelOrderService->execute($id);
            return response()->json(['message' => 'Order cancelled']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
