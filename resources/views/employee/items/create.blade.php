```blade
@extends('layouts.employee-layout')

@section('content')
<div class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Add New Inventory Item</h2>
            <a href="{{ route('employee.items.index') }}" class="modal-close" aria-label="Close modal">×</a>
        </div>
        
        <form action="{{ route('employee.items.store') }}" method="POST" class="inventory-form" id="inventoryForm">
            @csrf
            
            <div class="form-group">
                <label for="name" class="form-label">Item Name</label>
                <input type="text" 
                       class="form-control @error('name') is-invalid @enderror" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}" 
                       required 
                       aria-describedby="nameError"
                       placeholder="Enter item name">
                @error('name')
                    <div class="invalid-feedback" id="nameError">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="category" class="form-label">Category</label>
                <select class="form-control @error('category') is-invalid @enderror" 
                        id="category" 
                        name="category" 
                        required 
                        aria-describedby="categoryError">
                    <option value="" disabled selected>Select Category</option>
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
                    <div class="invalid-feedback" id="categoryError">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="status" class="form-label">Status</label>
                <select class="form-control @error('status') is-invalid @enderror" 
                        id="status" 
                        name="status" 
                        required 
                        aria-describedby="statusError">
                    <option value="" disabled selected>Select Status</option>
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
                    <div class="invalid-feedback" id="statusError">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-actions">
                <a href="{{ route('employee.items.index') }}" class="btn btn-cancel">Cancel</a>
                <button type="submit" class="btn btn-save">Save Item</button>
            </div>
        </form>
    </div>
</div>

<style>
* {
    box-sizing: border-box;
}

.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal-content {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    width: 100%;
    max-width: 500px;
    padding: 24px;
    position: relative;
    animation: slideIn 0.3s ease-out;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.modal-header h2 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
}

.modal-close {
    font-size: 1.5rem;
    color: #6b7280;
    text-decoration: none;
    transition: color 0.2s;
}

.modal-close:hover {
    color: #1f2937;
}

.inventory-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
    width: 100%;
}

.form-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
}

.form-control {
    width: 100% !important;
    padding: 5px 20px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 1rem;
    color: #1f2937;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.form-control:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-control.is-invalid {
    border-color: #ef4444;
}

.invalid-feedback {
    font-size: 0.75rem;
    color: #ef4444;
    margin-top: 4px;
}

.form-control option:disabled {
    color: #9ca3af;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 20px;
}

.btn {
    padding: 10px 16px;
    border-radius: 6px;
    font-size: 0.875rem;
    font-weight: 500;
    text-align: center;
    transition: background-color 0.2s, transform 0.1s;
    cursor: pointer;
}

.btn-cancel {
    background: #e5e7eb;
    color: #1f2937;
    border: none;
    text-decoration: none;
}

.btn-cancel:hover {
    background: #d1d5db;
}

.btn-save {
    background: #3b82f6;
    color: #fff;
    border: none;
}

.btn-save:hover {
    background: #2563eb;
}

.btn-save:active {
    transform: scale(0.98);
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 640px) {
    .modal-content {
        margin: 16px;
        padding: 16px;
    }

    .modal-header h2 {
        font-size: 1.25rem;
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

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('inventoryForm');
    const inputs = form.querySelectorAll('.form-control');

    inputs.forEach(input => {
        input.addEventListener('input', () => {
            if (input.classList.contains('is-invalid')) {
                input.classList.remove('is-invalid');
                const error = input.parentElement.querySelector('.invalid-feedback');
                if (error) error.style.display = 'none';
            }
        });
    });

    form.addEventListener('submit', (e) => {
        let hasErrors = false;
        inputs.forEach(input => {
            if (!input.value && input.required) {
                input.classList.add('is-invalid');
                const error = input.parentElement.querySelector('.invalid-feedback');
                if (error) error.style.display = 'block';
                hasErrors = true;
            }
        });

        if (hasErrors) {
            e.preventDefault();
        }
    });
});
</script>
@endsection
```