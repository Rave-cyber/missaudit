@extends('layouts.employee-layout')

@section('title', 'Receive Order Details')

@section('content')
<div class="container">
    <h2>Receive Order #{{ $receiveOrder->order_number }}</h2>

    <div class="mb-4">
        <p><strong>Supplier:</strong> {{ $receiveOrder->supplier->name }}</p>
        <p><strong>Status:</strong> {{ $receiveOrder->status }}</p>
        <p><strong>Total Price:</strong> ₱{{ number_format($receiveOrder->total_price, 2) }}</p>
    </div>

    <h4>Items</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Item</th>
                <th>Quantity</th>
                <th>Stocked In</th>
                <th>Unit Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($receiveOrder->items as $item)
                <tr>
                    <td>{{ $item->inventoryItem->name ?? 'N/A' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->stocked_in_quantity ?? 0 }}</td>
                    <td>₱{{ number_format($item->unit_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
