@extends('layouts.employee-layout')

@section('title', 'Receive Orders')

@section('content')
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Receive Orders</h2>
      <a href="{{ route('employee.receive-orders.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> New Receive Order
      </a>
    </div>

    <table class="table table-bordered table-hover">
      <thead class="thead-light">
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
            <td>{{ $order->created_at->format('Y-m-d') }}</td> {{-- assuming no dedicated date_received column --}}
            <td>{{ $order->items->sum('quantity') }}</td> {{-- if total_items is calculated --}}
            <td>
              <span class="badge badge-{{ $order->status === 'Completed' ? 'success' : 'warning' }}">
                {{ $order->status }}
              </span>
            </td>
            <td>
              <a href="{{ route('employee.receive-orders.show', $order->id) }}" class="btn btn-sm btn-info">View</a>
              <a href="{{ route('employee.receive-orders.edit', $order->id) }}" class="btn btn-sm btn-secondary">Edit</a>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center">
      {{ $receiveOrders->links() }}
    </div>
  </div>
@endsection
