<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <form>
                <div class="form-group">
                    <label for="item-name" class="form-label">Item Name</label>
                    <input type="text" class="form-control" id="item-name" name="name" 
                           value="{{ old('name', $inventoryItem->name ?? '') }}" required>
                    @error('name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="item-category" class="form-label">Category</label>
                    <select class="form-select" id="item-category" name="category" required>
                        <option value="">Select Category</option>
                        <option value="Cleaning Supplies" {{ (old('category', $inventoryItem->category ?? '') == 'Cleaning Supplies' ? 'selected' : '' }}>
                            Cleaning Supplies
                        </option>
                        <option value="Equipment" {{ (old('category', $inventoryItem->category ?? '') == 'Equipment' ? 'selected' : '' }}>
                            Equipment
                        </option>
                        <option value="Packaging" {{ (old('category', $inventoryItem->category ?? '') == 'Packaging' ? 'selected' : '' }}>
                            Packaging
                        </option>
                        <option value="Other" {{ (old('category', $inventoryItem->category ?? '') == 'Other' ? 'selected' : '' }}>
                            Other
                        </option>
                    </select>
                    @error('category')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="item-quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="item-quantity" name="quantity" 
                                   min="0" value="{{ old('quantity', $inventoryItem->quantity ?? 0) }}" required>
                            @error('quantity')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="item-price" class="form-label">Price ($)</label>
                            <input type="number" class="form-control" id="item-price" name="price" 
                                   min="0" step="0.01" value="{{ old('price', $inventoryItem->price ?? 0) }}" required>
                            @error('price')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="item-status" class="form-label">Status</label>
                    <select class="form-select" id="item-status" name="status" required>
                        <option value="In Stock" {{ (old('status', $inventoryItem->status ?? '') == 'In Stock' ? 'selected' : '' }}>
                            In Stock
                        </option>
                        <option value="Low Stock" {{ (old('status', $inventoryItem->status ?? '') == 'Low Stock' ? 'selected' : '' }}>
                            Low Stock
                        </option>
                        <option value="Out of Stock" {{ (old('status', $inventoryItem->status ?? '') == 'Out of Stock' ? 'selected' : '' }}>
                            Out of Stock
                        </option>
                    </select>
                    @error('status')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <button type="button" class="btn btn-secondary me-md-2">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Item</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>