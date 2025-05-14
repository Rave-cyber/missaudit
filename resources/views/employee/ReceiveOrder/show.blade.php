@extends('layouts.employee-layout')

@section('title', 'Receive Order Details')

@section('content')
<div class="container">
    <div class="header-section">
        <h2>Receive Order #{{ $receiveOrder->order_number }}</h2>
    </div>

    <div class="details-section mb-4">
        <p><strong>Supplier:</strong> {{ $receiveOrder->supplier->name }}</p>
        <p><strong>Status:</strong> {{ $receiveOrder->status }}</p>
        <p><strong>Total Price:</strong> ₱{{ number_format($receiveOrder->total_price, 2) }}</p>
    </div>

    <div class="table-section">
        <h4>Items</h4>
        <div class="table-wrapper">
            <table class="table">
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
    </div>
</div>

<style>
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 24px;
}

.header-section {
    margin-bottom: 24px;
}

.header-section h2 {
    font-size: 1.75rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
}

.details-section {
    background: #f9fafb;
    padding: 16px;
    border-radius: 6px;
    margin-bottom: 24px;
}

.details-section p {
    margin: 8px 0;
    font-size: 0.875rem;
    color: #374151;
}

.details-section strong {
    color: #1f2937;
}

.table-section {
    margin-bottom: 24px;
}

.table-section h4 {
    font-size: 1.25rem;
    font-weight: 500;
    color: #1f2937;
    margin-bottom: 16px;
}

.table-wrapper {
    overflow-x: auto;
    border-radius: 6px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
}

.table thead {
    background: #f3f4f6;
}

.table th {
    padding: 12px 16px;
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
}

.table td {
    padding: 12px 16px;
    font-size: 0.875rem;
    color: #1f2937;
    border-bottom: 1px solid #e5e7eb;
}

.table tbody tr:hover {
    background: #f9fafb;
}

.table tbody tr:last-child td {
    border-bottom: none;
}

@media (max-width: 768px) {
    .container {
        padding: 16px;
    }

    .details-section {
        padding: 12px;
    }

    .table th,
    .table td {
        font-size: 0.75rem;
        padding: 10px 12px;
    }
}
</style>
@endsection