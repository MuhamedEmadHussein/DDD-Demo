@extends('layouts.app')

@section('content')
<div class="animate-fade-in">
    <h1>Order Management Dashboard</h1>
    
    <div class="card">
        <h2>Recent Orders</h2>
        @if(empty($orders))
            <p style="color: var(--text-muted); text-align: center; padding: 2rem;">No orders found. Click "Place Demo Order" to start.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th>Total Price</th>
                        <th>Items</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td><span class="order-id">{{ substr($order->getId(), 0, 8) }}...</span></td>
                            <td>{{ $order->getCustomerId() }}</td>
                            <td>
                                <span class="status-badge status-{{ $order->getStatus()->value }}">
                                    {{ $order->getStatus()->label() }}
                                </span>
                            </td>
                            <td><span class="price">{{ $order->getTotalPrice()->format() }}</span></td>
                            <td>{{ count($order->getItems()) }} products</td>
                            <td>
                                @if($order->getStatus()->canBeCancelled())
                                    <button class="btn btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; background: var(--danger);" onclick="window.cancelOrder('{{ $order->getId() }}')">Cancel</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
        <div class="card">
            <h3>Domain-Driven Design Info</h3>
            <p style="color: var(--text-muted); line-height: 1.6;">
                This demo implements <strong>Domain-Driven Design (DDD)</strong>. Notice how business logic is isolated in the <code>app/Domains</code> directory, independent of the framework.
            </p>
        </div>
        <div class="card">
            <h3>Architecture Layers</h3>
            <ul style="color: var(--text-muted); line-height: 1.8; list-style-position: inside;">
                <li><strong>Domain:</strong> Logic & Rules</li>
                <li><strong>Application:</strong> Orchestration</li>
                <li><strong>Infrastructure:</strong> Persistence</li>
                <li><strong>Presentation:</strong> Controllers & Views</li>
            </ul>
        </div>
    </div>
</div>
@endsection
