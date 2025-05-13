@extends('layouts.employee-layout')

@section('title', 'Stock In Records')

@section('content')
<div class="container">
    <h2 class="mb-4">Stock In Records</h2>
    <a href="{{ route('employee.stock-in.form') }}" class="btn btn-primary mb-3">
    <i class="fas fa-plus"></i> New Stock In Transaction
    </a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="thead-light">
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
                    <td colspan="6" class="text-center">No stock-in records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $stockIns->links() }}
    </div>
</div>
@endsection
