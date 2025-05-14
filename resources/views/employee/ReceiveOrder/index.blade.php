@extends('layouts.employee-layout')

@section('title', 'Receive Orders')

@section('content')
<div class="container">
    <div class="header-section">
        <h2>Receive Orders</h2>
        <a href="{{ route('employee.receive-orders.create') }}" class="btn btn-primary" aria-label="Add new receive order">
            <i class="fas fa-plus"></i> New Receive Order
        </a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>RO Number</th>
                <th>Supplier</th>
                <th>Date Received</th>
                <th>Total Items</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($receiveOrders as $order)
                <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->supplier->name }}</td>
                    <td>{{ $order->created_at->format('Y-m-d') }}</td>
                    <td>{{ $order->items->sum('quantity') }}</td>
                    <td>
                        <span class="badge badge-{{ $order->status === 'Completed' ? 'success' : 'warning' }}">
                            {{ $order->status }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('employee.receive-orders.show', $order->id) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('employee.receive-orders.edit', $order->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="pagination-wrapper">
        {{ $receiveOrders->links() }}
    </div>
</div>

<style>
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.header-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.header-section h2 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    font-size: 0.875rem;
    font-weight: 500;
    border: none;
    border-radius: 4px;
    text-decoration: none;
    transition: background-color 0.2s;
}

.btn-primary {
    background: #3b82f6;
    color: #fff;
}

.btn-primary:hover {
    background: #2563eb;
}

.btn-info {
    background: #3b82f6;
    color: #fff;
    margin-right: 6px;
}

.btn-info:hover {
    background: #2563eb;
}

.btn-secondary {
    background: #6b7280;
    color: #fff;
}

.btn-secondary:hover {
    background: #4b5563;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 0.75rem;
}

.table-wrapper {
    overflow-x: auto;
    border-radius: 4px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
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
    padding: 12px;
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
}

.table td {
    padding: 12px;
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

.badge {
    padding: 6px 12px;
    font-size: 0.75rem;
    border-radius: 4px;
}

.badge-success {
    background: #10b981;
    color: #fff;
}

.badge-warning {
    background: #f59e0b;
    color: #fff;
}

.pagination-wrapper {
    margin-top: 20px;
    display: flex;
    justify-content: center;
}

.pagination-wrapper .page-link {
    padding: 8px 12px;
    margin: 0 4px;
    border: 1px solid #d1d5db;
    border-radius: 4px;
    font-size: 0.875rem;
    color: #3b82f6;
    text-decoration: none;
    transition: background-color 0.2s;
}

.pagination-wrapper .page-link:hover {
    background: #eff6ff;
    color: #2563eb;
}

.pagination-wrapper .page-item.active .page-link {
    background: #3b82f6;
    color: #fff;
    border-color: #3b82f6;
}

.pagination-wrapper .page-item.disabled .page-link {
    color: #9ca3af;
    border-color: #d1d5db;
    background: #f3f4f6;
    cursor: not-allowed;
}

@media (max-width: 768px) {
    .container {
        padding: 16px;
    }

    .header-section {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }

    .btn {
        width: 100%;
        justify-content: center;
    }

    .table th,
    .table td {
        font-size: 0.75rem;
        padding: 8px;
    }

    .badge {
        font-size: 0.7rem;
        padding: 4px 8px;
    }

    .pagination-wrapper .page-link {
        padding: 6px 10px;
        margin: 0 2px;
    }
}
</style>
@endsection