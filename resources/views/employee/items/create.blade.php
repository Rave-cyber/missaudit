@extends('layouts.employee-layout')

@section('content')
<div class="modal-content">
    <h2>Add New Inventory Item</h2>
    
    <form action="{{ route('employee.items.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="name">Item Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                   id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="category">Category</label>
            <select class="form-control @error('category') is-invalid @enderror" 
                    id="category" name="category" required>
                <option value="">Select Category</option>
                <option value="Cleaning Supplies" {{ old('category') == 'Cleaning Supplies' ? 'selected' : '' }}>
                    Cleaning Supplies
                </option>
                <option value="Equipment" {{ old('category') == 'Equipment' ? 'selected' : '' }}>
                    Equipment
                </option>
                <option value="Packaging" {{ old('category') == 'Packaging' ? 'selected' : '' }}>
                    Packaging
                </option>
            </select>
            @error('category')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>


        <!-- <div class="form-group">
            <label for="price">Price ($)</label>
            <input type="number" class="form-control @error('price') is-invalid @enderror" 
                   id="price" name="price" min="0" step="0.01" value="{{ old('price', 0) }}" required>
            @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div> -->

        <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control @error('status') is-invalid @enderror" 
                    id="status" name="status" required>
                <option value="In Stock" {{ old('status') == 'In Stock' ? 'selected' : '' }}>
                    In Stock
                </option>
                <option value="Low Stock" {{ old('status') == 'Low Stock' ? 'selected' : '' }}>
                    Low Stock
                </option>
                <option value="Out of Stock" {{ old('status') == 'Out of Stock' ? 'selected' : '' }}>
                    Out of Stock
                </option>
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('employee.items.index') }}" class="cancel-btn">Cancel</a>
            <button type="submit" class="save-btn">Save Item</button>
        </div>
    </form>
</div>
@endsection
