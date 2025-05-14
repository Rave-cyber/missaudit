@extends('layouts.employee-layout')

@section('content')
<div class="container">
    <div class="header-section">
        <h2>Stock In from Receive Order</h2>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif

    @if($receiveOrders->isNotEmpty())
    <form method="GET" action="{{ route('employee.stock-in.form') }}" class="mb-4">
        <div class="form-group">
            <label for="receive_order_id" class="form-label">Select Receive Order</label>
            <select name="receive_order_id" onchange="this.form.submit()" class="form-control" id="receive_order_id" aria-describedby="orderSelectHelp">
                <option value="">-- Select --</option>
                @foreach($receiveOrders as $ro)
                    <option value="{{ $ro->id }}" {{ request('receive_order_id') == $ro->id ? 'selected' : '' }}>
                        Order #{{ $ro->order_number }} ({{ $ro->supplier->name }})
                    </option>
                @endforeach
            </select>
            <small id="orderSelectHelp" class="form-text text-muted">Choose an order to view available items.</small>
        </div>
    </form>
    @endif

    @if($selectedOrder)
    <form action="{{ route('employee.stock-in.from-ro.submit', $selectedOrder->id) }}" method="POST" class="stock-in-form">
        @csrf
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Ordered Qty</th>
                        <th>Already Stocked</th>
                        <th>Remaining</th>
                        <th>Stock In Now</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($selectedOrder->items as $roItem)
                        @php
                            $remaining = $roItem->quantity - $roItem->stocked_in_quantity;
                        @endphp
                        @if($remaining > 0)
                        <tr>
                            <td>{{ $roItem->inventoryItem->name }}</td>
                            <td>{{ $roItem->quantity }}</td>
                            <td>{{ $roItem->stocked_in_quantity }}</td>
                            <td>{{ $remaining }}</td>
                            <td>
                                <input type="number" name="items[{{ $roItem->id }}][quantity]" class="form-control" max="{{ $remaining }}" min="1" required>
                                <input type="hidden" name="items[{{ $roItem->id }}][id]" value="{{ $roItem->id }}">
                            </td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        <button type="submit" class="btn btn-primary mt-4">Stock In Selected Items</button>
    </form>
    @endif
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

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
    max-width: 400px;
}

.form-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
}

.form-control {
    padding: 6px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 0.875rem;
    color: #1f2937;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.form-control:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-text {
    font-size: 0.75rem;
    color: #6b7280;
}

.alert {
    padding: 12px 16px;
    border-radius: 6px;
    margin-bottom: 24px;
    animation: fadeIn 0.3s ease-out;
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #6ee7b7;
}

.alert-danger {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

.table-wrapper {
    overflow-x: auto;
    border-radius: 6px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 16px;
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

.btn-primary {
    padding: 10px 16px;
    background: #3b82f6;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 0.875rem;
    font-weight: 500;
    transition: background-color 0.2s, transform 0.1s;
    cursor: pointer;
}

.btn-primary:hover {
    background: #2563eb;
}

.btn-primary:active {
    transform: scale(0.98);
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 768px) {
    .container {
        padding: 16px;
    }

    .form-group {
        max-width: 100%;
    }

    .table th,
    .table td {
        font-size: 0.75rem;
        padding: 10px 12px;
    }
}
</style>
@endsection