@extends('layouts.employee-layout')

@section('content')
<div class="container">
    <h2>Stock In from Receive Order</h2>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($receiveOrders->isNotEmpty())
    <form method="GET" action="{{ route('employee.stock-in.form') }}">
        <div class="form-group">
            <label for="receive_order_id">Select Receive Order</label>
            <select name="receive_order_id" onchange="this.form.submit()" class="form-control">
                <option value="">-- Select --</option>
                @foreach($receiveOrders as $ro)
                    <option value="{{ $ro->id }}" {{ request('receive_order_id') == $ro->id ? 'selected' : '' }}>
                        Order #{{ $ro->order_number }} ({{ $ro->supplier->name }})
                    </option>
                @endforeach
            </select>
        </div>
    </form>
    @endif

    @if($selectedOrder)
    {{-- Display items from selected receive order --}}
    <form action="{{ route('employee.stock-in.from-ro.submit', $selectedOrder->id) }}" method="POST">
        @csrf
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
        <button type="submit" class="btn btn-primary">Stock In Selected Items</button>
    </form>
    @endif
</div>
@endsection
