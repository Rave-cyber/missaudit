@extends('layouts.employee-layout')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="h5 mb-0">Edit Supplier</h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('employee.supplier.update', $supplier->id) }}" method="POST" id="editSupplierForm">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name" class="form-label">Supplier Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $supplier->name }}" required aria-describedby="nameHelp">
                            <small id="nameHelp" class="form-text text-muted">Enter the supplier's name.</small>
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $supplier->email }}" required aria-describedby="emailHelp">
                            <small id="emailHelp" class="form-text text-muted">Enter a valid email address.</small>
                        </div>
                        <div class="form-group">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ $supplier->phone }}" required aria-describedby="phoneHelp">
                            <small id="phoneHelp" class="form-text text-muted">Enter the supplier's phone number.</small>
                        </div>
                        <div class="form-group">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" aria-describedby="addressHelp">{{ $supplier->address }}</textarea>
                            <small id="addressHelp" class="form-text text-muted">Enter the supplier's address (optional).</small>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('employee.supplier.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.container-fluid {
    padding: 24px;
}

.card {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.card-header {
    padding: 16px;
    background: #3b82f6;
    border-bottom: 1px solid #e5e7eb;
    border-radius: 8px 8px 0 0;
}

.card-header .h5 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #fff;
    margin: 0;
}

.card-body {
    padding: 24px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 20px;
}

.form-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
}

.form-control {
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 0.875rem;
    color: #1f2937;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.form-control:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-text {
    font-size: 0.75rem;
    color: #6b7280;
}

.form-actions {
    display: flex;
    gap: 12px;
    margin-top: 20px;
}

.btn {
    padding: 10px 16px;
    border-radius: 6px;
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    transition: background-color 0.2s;
}

.btn-primary {
    background: #3b82f6;
    color: #fff;
    border: none;
}

.btn-primary:hover {
    background: #2563eb;
}

.btn-secondary {
    background: #6b7280;
    color: #fff;
    border: none;
    text-decoration: none;
}

.btn-secondary:hover {
    background: #4b5563;
}

@media (max-width: 768px) {
    .container-fluid {
        padding: 16px;
    }

    .card-body {
        padding: 16px;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-actions {
        flex-direction: column;
        gap: 8px;
    }

    .btn {
        width: 100%;
    }
}
</style>
@endsection