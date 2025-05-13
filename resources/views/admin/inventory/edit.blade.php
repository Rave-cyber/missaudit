@extends('layouts.admin')

@section('content')
<div class="modal-content">
    <h2>Edit Inventory Item</h2>
    <form action="{{ route('admin.inventory.update', $inventoryItem->id) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.inventory._form')
        <div class="form-actions">
            <a href="{{ route('admin.inventory.index') }}" class="cancel-btn">Cancel</a>
            <button type="submit" class="save-btn">Update Item</button>
        </div>
    </form>
</div>
@endsection