@extends('layouts.employee-layout')

@section('content')
<div class="container">
    <h2>Create Purchase Order</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('employee.receive-orders.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="supplier_id">Supplier</label>
            <select name="supplier_id" id="supplier_id" class="form-control" required>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Table to display selected items -->
        <h4>Selected Items</h4>
        <table class="table table-bordered" id="selected-items-table">
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

        <!-- Form for adding new items -->
        <div class="card mb-3">
            <div class="card-header">Add New Item</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="new_item_id">Item</label>
                            <select id="new_item_id" class="form-control">
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="new_quantity">Quantity</label>
                            <input type="number" id="new_quantity" class="form-control" value="1" min="1">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="new_price">Price</label>
                            <input type="number" id="new_price" class="form-control" step="0.01" value="0.00" min="0">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="button" id="add-item" class="btn btn-primary btn-block">Add Item</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hidden inputs will be added here by JavaScript -->
        <div id="hidden-inputs"></div>

        <button type="submit" class="btn btn-success">Submit Order</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const items = [];
        let itemCounter = 0;
        
        // Function to update the grand total
        function updateGrandTotal() {
            const grandTotal = items.reduce((sum, item) => sum + (item.quantity * item.price), 0);
            document.getElementById('grand-total').textContent = grandTotal.toFixed(2);
        }
        
        // Function to add an item to the table
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
            
            // Add event listener for remove button
            row.querySelector('.remove-item').addEventListener('click', function() {
                removeItem(item.id);
            });
        }
        
        // Function to remove an item
        function removeItem(id) {
            // Remove from array
            const index = items.findIndex(item => item.id === id);
            if (index !== -1) {
                items.splice(index, 1);
            }
            
            // Remove from table
            document.querySelector(`#selected-items-table tbody tr[data-id="${id}"]`).remove();
            updateGrandTotal();
            updateHiddenInputs();
        }
        
        // Function to update hidden inputs
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
        
        // Add item when 'Add Item' button is clicked
        document.getElementById('add-item').addEventListener('click', function() {
            const itemSelect = document.getElementById('new_item_id');
            const quantityInput = document.getElementById('new_quantity');
            const priceInput = document.getElementById('new_price');
            
            // Validate inputs
            if (!itemSelect.value || !quantityInput.value || !priceInput.value) {
                alert('Please fill all fields');
                return;
            }
            
            if (parseFloat(quantityInput.value) <= 0 || parseFloat(priceInput.value) < 0) {
                alert('Quantity must be greater than 0 and price cannot be negative');
                return;
            }
            
            // Create new item
            const newItem = {
                id: itemCounter++,
                item_id: itemSelect.value,
                name: itemSelect.options[itemSelect.selectedIndex].text,
                quantity: parseFloat(quantityInput.value),
                price: parseFloat(priceInput.value)
            };
            
            // Add to array
            items.push(newItem);
            
            // Add to table
            addItemToTable(newItem);
            
            // Update hidden inputs
            updateHiddenInputs();
            
            // Reset form
            quantityInput.value = '1';
            priceInput.value = '0.00';
        });
    });
</script>
@endsection