@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Manage Service Prices</h1>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.service-prices.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="card">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Service Name</th>
                            <th>Base Price ($)</th>
                            <th>Weight Limit (kg)</th>
                            <th>Extra Rate ($/kg)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($prices as $index => $price)
                        <tr>
                            <td>
                                <input type="hidden" name="prices[{{ $index }}][id]" value="{{ $price->id }}">
                                <input type="text" name="prices[{{ $index }}][service_name]" 
                                       value="{{ $price->service_name }}" 
                                       class="form-control" required>
                            </td>
                            <td>
                                <input type="number" step="0.01" min="0" 
                                       name="prices[{{ $index }}][base_price]" 
                                       value="{{ $price->base_price }}" 
                                       class="form-control" required>
                            </td>
                            <td>
                                <input type="number" step="0.01" min="0" 
                                       name="prices[{{ $index }}][weight_limit]" 
                                       value="{{ $price->weight_limit }}" 
                                       class="form-control" required>
                            </td>
                            <td>
                                <input type="number" step="0.01" min="0" 
                                       name="prices[{{ $index }}][extra_rate]" 
                                       value="{{ $price->extra_rate }}" 
                                       class="form-control" required>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Update Prices</button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection