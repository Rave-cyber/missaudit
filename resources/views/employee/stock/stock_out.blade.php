@extends('layouts.employee-layout')

@section('content')
<div class="container">
    <h2>Stock Out</h2>

    {{-- Success and Error Message Alerts --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Stock Out Form --}}
    <form action="{{ route('employee.stock-out') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="item_id">Item</label>
            <select name="item_id" id="item_id" class="form-control" required>
                <option value="">Select Item</option>
                @foreach($items as $item)
                    <option value="{{ $item->id }}">{{ $item->name }} (Current Qty: {{ $item->quantity }})</option>
                @endforeach
            </select>
            @error('item_id') <div class="alert alert-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="quantity">Quantity to Stock Out</label>
            <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
            @error('quantity') <div class="alert alert-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="reason">Reason for Stock Out</label>
            <input type="text" name="reason" id="reason" class="form-control" required>
            @error('reason') <div class="alert alert-danger">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-danger">Stock Out</button>
    </form>
</div>
@endsection
