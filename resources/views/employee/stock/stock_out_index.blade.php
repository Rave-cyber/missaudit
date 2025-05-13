@extends('layouts.employee-layout')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Stock Out Transactions</h2>
        <a href="{{ route('employee.stock-out.form') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Stock Out
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered table-hover">
        <thead class="thead-light">
            <tr>
                <th>Item</th>
                <th>Quantity</th>
                <th>Reason</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stockOuts as $stock)
                <tr>
                    <td>{{ $stock->item->name ?? 'N/A' }}</td>
                    <td>{{ $stock->quantity }}</td>
                    <td>{{ $stock->reason }}</td>
                    <td>{{ $stock->created_at->format('Y-m-d H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No stock out transactions found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $stockOuts->links() }}
    </div>
</div>
@endsection
