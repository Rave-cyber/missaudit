@extends('layouts.employee-layout')

@section('content')
<div class="container">
    <div class="header-section">
        <h2>Create Purchase Order</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('employee.receive-orders.store') }}" method="POST" id="purchaseOrderForm">
        @csrf

        <div class="form-group">
            <label for="supplier_id" class="form-label">Supplier</label>
            <select name="supplier_id" id="supplier_id" class="form-control" required aria-describedby="supplierHelp">
                <option value="" disabled selected>-- Select Supplier --</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                @endforeach
            </select>
            <small id="supplierHelp" class="form-text text-muted">Select the supplier for this purchase order.</small>
        </div>

        <!-- Table to display selected items -->
        <div class="table-section">
            <h4>Selected Items</h4>
            <div class="table-wrapper">
                <table class="table" id="selected-items-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dynamic rows will be added here -->
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3">Grand Total</th>
                            <th id="grand-total">0.00</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Form for adding new items -->
        <div class="card mb-4">
            <div class="card-header">Add New Item</div>
            <div class="card-body">
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="new_item_id" class="form-label">Item</label>
                            <select id="new_item_id" class="form-control">
                                <option value="" disabled selected>-- Select Item --</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="new_quantity" class="form-label">Quantity</label>
                            <input type="number" id="new_quantity" class="form-control" value="1" min="1">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="new_price" class="form-label">Price</label>
                            <input type="number" id="new_price" class="form-control" step="0.01" value="0.00" min="0">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="button" id="add-item" class="btn btn-primary btn-block">Add Item</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hidden inputs will be added here by JavaScript -->
        <div id="hidden-inputs"></div>

        <button type="submit" class="btn btn-success">Submit Order</button>
    </form>
</div>

<style>
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 24px;
}

.header-section {
    margin-bottom: 24px;
}

.header-section h2 {
    font-size: 1.75rem;
    font-weight: 600;
    color: #1f2937;
}

.alert {
    padding: 12px 16px;
    border-radius: 6px;
    margin-bottom: 24px;
    animation: fadeIn 0.3s ease-out;
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #6ee7b7;
}

.alert-danger {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 24px;
    max-width: 400px;
}

.form-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
}

.form-control {
    padding: 6px 12px;
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

.table-section {
    margin-bottom: 24px;
}

.table-section h4 {
    font-size: 1.25rem;
    font-weight: 500;
    color: #1f2937;
    margin-bottom: 16px;
}

.table-wrapper {
    overflow-x: auto;
    border-radius: 6px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
}

.table thead {
    background: #f3f4f6;
}

.table th {
    padding: 12px 16px;
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
}

.table td {
    padding: 12px 16px;
    font-size: 0.875rem;
    color: #1f2937;
    border-bottom: 1px solid #e5e7eb;
}

.table tbody tr:hover {
    background: #f9fafb;
}

.table tbody tr:last-child td {
    border-bottom: none;
}

.table tfoot th {
    padding: 12px 16px;
    font-weight: 600;
    color: #1f2937;
    text-align: left;
}

.card {
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    background: #fff;
}

.card-header {
    padding: 12px 16px;
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
    font-size: 1rem;
    font-weight: 500;
    color: #1f2937;
}

.card-body {
    padding: 16px;
}

.btn {
    padding: 10px 16px;
    border-radius: 6px;
    font-size: 0.875rem;
    font-weight: 500;
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

.btn-danger {
    background: #ef4444;
    color: #fff;
    border: none;
}

.btn-danger:hover {
    background: #dc2626;
}

.btn-success {
    background: #10b981;
    color: #fff;
    border: none;
}

.btn-success:hover {
    background: #059669;
}

.btn-block {
    width: 100%;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 768px) {
    .container {
        padding: 16px;
    }

    .form-group {
        max-width: 100%;
    }

    .table th,
    .table td {
        font-size: 0.75rem;
        padding: 10px 12px;
    }

    .row {
        flex-direction: column;
        gap: 16px;
    }

    .col-md-4,
    .col-md-2 {
        width: 100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const items = [];
    let itemCounter = 0;

    function updateGrandTotal() {
        const grandTotal = items.reduce((sum, item) => sum + (item.quantity * item.price), 0);
        document.getElementById('grand-total').textContent = grandTotal.toFixed(2);
    }

    function addItemToTable(item) {
        const tableBody = document.querySelector('#selected-items-table tbody');
        const row = document.createElement('tr');
        row.dataset.id = item.id;

        const total = item.quantity * item.price;

        row.innerHTML = `
            <td>${item.name}</td>
            <td>${item.quantity}</td>
            <td>${item.price.toFixed(2)}</td>
            <td>${total.toFixed(2)}</td>
            <td>
                <button type="button" class="btn btn-danger btn-sm remove-item" data-id="${item.id}">Remove</button>
            </td>
        `;

        tableBody.appendChild(row);
        updateGrandTotal();

        row.querySelector('.remove-item').addEventListener('click', function() {
            removeItem(item.id);
        });
    }

    function removeItem(id) {
        const index = items.findIndex(item => item.id === id);
        if (index !== -1) {
            items.splice(index, 1);
        }

        document.querySelector(`#selected-items-table tbody tr[data-id="${id}"]`).remove();
        updateGrandTotal();
        updateHiddenInputs();
    }

    function updateHiddenInputs() {
        const container = document.getElementById('hidden-inputs');
        container.innerHTML = '';

        items.forEach((item, index) => {
            container.innerHTML += `
                <input type="hidden" name="items[${index}][item_id]" value="${item.item_id}">
                <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
                <input type="hidden" name="items[${index}][price]" value="${item.price}">
            `;
        });
    }

    document.getElementById('add-item').addEventListener('click', function(e) {
        e.preventDefault();
        const itemSelect = document.getElementById('new_item_id');
        const quantityInput = document.getElementById('new_quantity');
        const priceInput = document.getElementById('new_price');

        if (!itemSelect.value || !quantityInput.value || !priceInput.value) {
            alert('Please fill all fields');
            return;
        }

        const quantity = parseFloat(quantityInput.value);
        const price = parseFloat(priceInput.value);

        if (quantity <= 0) {
            alert('Quantity must be greater than 0');
            return;
        }

        if (price < 0) {
            alert('Price cannot be negative');
            return;
        }

        const newItem = {
            id: itemCounter++,
            item_id: itemSelect.value,
            name: itemSelect.options[itemSelect.selectedIndex].text,
            quantity: quantity,
            price: price
        };

        items.push(newItem);
        addItemToTable(newItem);
        updateHiddenInputs();

        quantityInput.value = '1';
        priceInput.value = '0.00';
        itemSelect.value = '';
    });

    document.getElementById('purchaseOrderForm').addEventListener('submit', function(e) {
        if (items.length === 0) {
            e.preventDefault();
            alert('Please add at least one item to the purchase order.');
        }
    });
});
</script>
@endsection