@extends('layouts.employee-layout')

@section('title', 'Stock In Records')

@section('content')
<div class="container">
    <div class="header-section">
        <h2>Stock In Records</h2>
        <a href="{{ route('employee.stock-in.form') }}" class="btn btn-primary" aria-label="Add new stock in transaction">
            <i class="fas fa-plus"></i> New Stock In Transaction
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Supplier</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Reason</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stockIns as $stock)
                    <tr>
                        <td>{{ $stock->item->name ?? 'N/A' }}</td>
                        <td>{{ $stock->supplier->name ?? 'N/A' }}</td>
                        <td>{{ $stock->quantity }}</td>
                        <td>₱{{ number_format($stock->price, 2) }}</td>
                        <td>{{ $stock->reason ?? '-' }}</td>
                        <td>{{ $stock->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="no-records">No stock-in records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrapper">
        {{ $stockIns->links() }}
    </div>
</div>

<style>
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 24px;
}

.header-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.header-section h2 {
    font-size: 1.75rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
}

.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    background: #3b82f6;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    transition: background-color 0.2s, transform 0.1s;
}

.btn-primary:hover {
    background: #2563eb;
}

.btn-primary:active {
    transform: scale(0.98);
}

.alert-success {
    padding: 12px 16px;
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #6ee7b7;
    border-radius: 6px;
    margin-bottom: 24px;
    animation: fadeIn 0.3s ease-out;
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

.no-records {
    text-align: center;
    color: #6b7280;
    font-style: italic;
}

.pagination-wrapper {
    margin-top: 24px;
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
    transition: background-color 0.2s, color 0.2s;
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

    .header-section {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }

    .header-section h2 {
        font-size: 1.5rem;
    }

    .table th,
    .table td {
        font-size: 0.75rem;
        padding: 10px 12px;
    }
}
</style>
@endsection