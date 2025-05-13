@extends('layouts.employee-layout')

@section('title', 'Order Management')

@section('content')
<div class="container-fluid">
    <div class="page-title">Order Management</div>
    
    <div class="order-cards-container">
        @foreach ($activeOrders as $order)
        <div class="order-card">
            <div class="order-card-header">
                <span class="order-id">Order #{{ $order->id }}</span>
                <span class="order-date">{{ $order->date }}</span>
            </div>
            
            <div class="order-customer">{{ $order->order_name }}</div>
            
            <div class="order-details">
                <div class="detail-row">
                    <span class="detail-label">Service Type:</span>
                    <span class="detail-value">
                    @if(is_array($order->service_type))
                        {{ implode(', ', $order->service_type) }}
                    @else
                        {{ $order->service_type }}
                    @endif
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Weight:</span>
                    <span class="detail-value">{{ $order->weight }} kg</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Payment Method:</span>
                    <span class="detail-value">{{ $order->payment_method }}</span>
                </div>
            </div>
            
            <div class="order-amount">${{ number_format($order->amount, 2) }}</div>
            
            <div class="detail-row">
                <span>
                    Status: 
                    <span class="order-status status-{{ strtolower(str_replace(' ', '-', $order->status)) }}">
                        {{ $order->status }}
                    </span>
                </span>
                <span>
                    Payment: 
                    <span class="payment-status payment-{{ $order->payment_status ?? 'pending' }}">
                        {{ $order->payment_status ?? 'Pending' }}
                    </span>
                </span>
            </div>
            
            <select class="status-select" data-order-id="{{ $order->id }}" onchange="updateOrderStatus(this)">
                <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Washing" {{ $order->status == 'Washing' ? 'selected' : '' }}>Washing</option>
                <option value="Drying" {{ $order->status == 'Drying' ? 'selected' : '' }}>Drying</option>
                <option value="Ironing" {{ $order->status == 'Ironing' ? 'selected' : '' }}>Ironing</option>
                <option value="Ready" {{ $order->status == 'Ready' ? 'selected' : '' }}>Ready for Pickup</option>
                <option value="Completed" {{ $order->status == 'Completed' ? 'selected' : '' }}>Completed</option>
            </select>
            
            <div class="order-actions">
                <button class="btn-action btn-view" onclick="viewOrderDetails({{ $order->id }})">
                    <i class="fas fa-eye"></i> View
                </button>
                @if($order->status == 'Completed' && ($order->payment_status ?? 'pending') == 'paid')
                    <button class="btn-action btn-archive" onclick="archiveOrder({{ $order->id }})">
                        <i class="fas fa-archive"></i> Archive
                    </button>
                @else
                    <button class="btn-action btn-mark-paid" onclick="markAsPaid({{ $order->id }})" 
                        {{ ($order->payment_status ?? 'pending') == 'paid' ? 'disabled' : '' }}>
                        <i class="fas fa-check-circle"></i> Mark Paid
                    </button>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-5">
        <button class="btn btn-secondary" type="button" data-toggle="collapse" data-target="#archivedOrders">
            <i class="fas fa-archive"></i> Show Archived Orders ({{ $archivedOrders->count() }})
        </button>
        <div class="collapse mt-3" id="archivedOrders">
            <div class="order-cards-container">
                @foreach ($archivedOrders as $order)
                <div class="order-card archived">
                    <div class="order-card-header">
                        <span class="order-id">Order #{{ $order->id }}</span>
                        <span class="order-date">{{ $order->date }}</span>
                    </div>
                    
                    <div class="order-customer">{{ $order->order_name }}</div>
                    
                    <div class="order-details">
                        <div class="detail-row">
                            <span class="detail-label">Service Type:</span>
                            <span class="detail-value">
                                @if(is_array($order->service_type))
                                    {{ implode(', ', $order->service_type) }}
                                @else
                                    {{ $order->service_type }}
                                @endif
                            </span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Amount:</span>
                            <span class="detail-value">${{ number_format($order->amount, 2) }}</span>
                        </div>
                    </div>
                    
                    <div class="detail-row">
                        <span>
                            Status: 
                            <span class="order-status status-completed">
                                {{ $order->status }}
                            </span>
                        </span>
                        <span>
                            Payment: 
                            <span class="payment-status payment-paid">
                                Paid
                            </span>
                        </span>
                    </div>
                    
                    <div class="order-actions">
                        <button class="btn-action btn-view" onclick="viewOrderDetails({{ $order->id }})">
                            <i class="fas fa-eye"></i> View
                        </button>
                        <button class="btn-action btn-archive" onclick="unarchiveOrder({{ $order->id }})">
                            <i class="fas fa-undo"></i> Restore
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="orderDetailsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Order Details - #<span id="modalOrderId"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="orderDetailsContent">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="printReceipt()">
                    <i class="fas fa-print"></i> Print Receipt
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    :root {
        --primary-color: #079CD6;
        --secondary-color: #2F356D;
        --accent-color: #17E8FF;
        --light-bg: rgba(255, 255, 255, 0.9);
    }
     .main-content {
        overflow-y: auto;
        height: calc(100vh - 40px); /* Adjust padding/margins as needed */
        padding: 20px;
    }
    
    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: var(--secondary-color);
        margin-bottom: 25px;
        position: relative;
        padding-bottom: 10px;
    }
    
    .page-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 4px;
        background: var(--primary-color);
        border-radius: 2px;
    }
    
    .order-cards-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }
    
    .order-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }
    
    .order-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }
    
    .order-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }
    
    .order-id {
        font-weight: 700;
        color: var(--secondary-color);
    }
    
    .order-date {
        color: #6c757d;
        font-size: 0.9rem;
    }
    
    .order-customer {
        font-weight: 600;
        margin-bottom: 10px;
        color: var(--secondary-color);
    }
    
    .order-details {
        margin-bottom: 15px;
    }
    
    .detail-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
    }
    
    .detail-label {
        color: #6c757d;
    }
    
    .detail-value {
        font-weight: 500;
    }
    
    .order-status {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-weight: bold;
        text-transform: capitalize;
        font-size: 0.85rem;
        margin-top: 5px;
    }
    
    .status-pending {
        background-color: #FFD700;
        color: #000;
    }
    
    .status-washing {
        background-color: #1E90FF;
        color: #FFF;
    }
    
    .status-drying {
        background-color: #9370DB;
        color: #FFF;
    }
    
    .status-ironing {
        background-color: #FF8C00;
        color: #FFF;
    }
    
    .status-ready {
        background-color: #32CD32;
        color: #FFF;
    }
    
    .status-completed {
        background-color: #228B22;
        color: #FFF;
    }
    
    .payment-status {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-weight: bold;
        font-size: 0.85rem;
    }
    
    .payment-pending {
        background-color: #FF6347;
        color: #FFF;
    }
    
    .payment-paid {
        background-color: #20B2AA;
        color: #FFF;
    }
    
    .order-amount {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--primary-color);
        margin: 10px 0;
    }
    
    .order-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 15px;
    }
    
    .btn-action {
        border-radius: 8px;
        padding: 8px 15px;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }
    
    .btn-view {
        background: var(--secondary-color);
        color: white;
    }
    
    .btn-view:hover {
        background: #1e2258;
    }
    
    .btn-update {
        background: var(--primary-color);
        color: white;
    }
    
    .btn-update:hover {
        background: #057baa;
    }
    
    .btn-mark-paid {
        background: #28a745;
        color: white;
    }
    
    .btn-mark-paid:hover {
        background: #218838;
    }

    .btn-archive {
        background: #6c757d;
        color: white;
    }

    .btn-archive:hover {
        background: #5a6268;
    }
    
    .status-select {
        width: 100%;
        padding: 8px;
        border-radius: 8px;
        border: 1px solid #ddd;
        margin-top: 10px;
    }
    
    @media (max-width: 768px) {
        .order-cards-container {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    function viewOrderDetails(orderId) {
        $.ajax({
            url: '/orders/' + orderId,
            method: 'GET',
            success: function(data) {
                $('#modalOrderId').text(data.id);
                const statusLogs = Array.isArray(data.status_logs) ? data.status_logs : [];
                const formattedDate = data.date ? new Date(data.date).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                }) : 'N/A';
                $('#orderDetailsContent').html(`
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Customer Name:</strong> ${data.order_name || 'N/A'}</p>
                            <p><strong>Order Date:</strong> ${formattedDate}</p>
                            <p><strong>Weight:</strong> ${data.weight ? data.weight + ' kg' : 'N/A'}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Status:</strong> 
                                <span class="order-status status-${data.status ? data.status.toLowerCase().replace(' ', '-') : 'unknown'}">
                                    ${data.status || 'Unknown'}
                                </span>
                            </p>
                            <p><strong>Payment:</strong> 
                                <span class="payment-status payment-${data.payment_status || 'pending'}">
                                    ${data.payment_status || 'Pending'}
                                </span>
                            </p>
                            <p><strong>Amount:</strong> $${data.amount ? parseFloat(data.amount).toFixed(2) : '0.00'}</p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <p><strong>Service Type:</strong> ${data.service_type && Array.isArray(data.service_type) && data.service_type.length > 0 ? data.service_type.join(', ') : 'N/A'}</p>
                            <p><strong>Payment Method:</strong> ${data.payment_method || 'N/A'}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Special Instructions:</strong> ${data.special_instructions || 'None'}</p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Status History</h5>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>Changed At</th>
                                        <th>Changed By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${statusLogs.length > 0 ? statusLogs.map(log => `
                                        <tr>
                                            <td>${log.status || 'N/A'}</td>
                                            <td>${log.changed_at ? new Date(log.changed_at).toLocaleString() : 'N/A'}</td>
                                            <td>${log.user && log.user.name ? log.user.name : 'N/A'}</td>
                                        </tr>
                                    `).join('') : `
                                        <tr>
                                            <td colspan="3">No status history available</td>
                                        </tr>
                                    `}
                                </tbody>
                            </table>
                        </div>
                    </div>
                `);
                $('#orderDetailsModal').modal('show');
            },
            error: function(xhr) {
                console.error('Error fetching order details:', xhr.responseJSON);
                let errorMessage = xhr.responseJSON?.error || xhr.responseJSON?.message || 'Failed to load order details.';
                alert('Error: ' + errorMessage);
            }
        });
    }

    function updateOrderStatus(selectElement) {
        const orderId = selectElement.dataset.orderId;
        const newStatus = selectElement.value;
        
        $.ajax({
            url: '/orders/' + orderId + '/status',
            method: 'PUT',
            data: {
                _token: '{{ csrf_token() }}',
                status: newStatus
            },
            success: function(response) {
                const card = selectElement.closest('.order-card');
                const badge = card.querySelector('.order-status');
                
                badge.className = 'order-status';
                badge.classList.add('status-' + newStatus.toLowerCase().replace(' ', '-'));
                badge.textContent = newStatus;
                
                alert(response.success);
                
                if (newStatus === 'Completed') {
                    location.reload();
                }
            },
            error: function(xhr) {
                console.error('Error response:', xhr.responseJSON);
                let errorMessage = 'An error occurred while updating the status.';
                if (xhr.responseJSON) {
                    if (xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    } else if (xhr.responseJSON.errors) {
                        errorMessage = Object.values(xhr.responseJSON.errors).flat().join(', ');
                    }
                }
                alert('Error updating status: ' + errorMessage);
            }
        });
    }

    function markAsPaid(orderId) {
        if (!confirm('Mark this order as paid? This will also archive the order if it is completed.')) return;
        
        $.ajax({
            url: '/orders/' + orderId + '/mark-paid',
            method: 'PUT',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                alert(response.message);
                location.reload();
            },
            error: function(xhr) {
                console.error('Error response:', xhr.responseJSON);
                let errorMessage = xhr.responseJSON?.error || xhr.responseJSON?.message || 'An error occurred.';
                alert('Error: ' + errorMessage);
            }
        });
    }

    function archiveOrder(orderId) {
        if (!confirm('Archive this order?')) return;
        
        $.ajax({
            url: '/orders/' + orderId + '/archive',
            method: 'PUT',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                alert(response.message);
                location.reload();
            },
            error: function(xhr) {
                console.error('Error response:', xhr.responseJSON);
                let errorMessage = xhr.responseJSON?.error || xhr.responseJSON?.message || 'An error occurred.';
                alert('Error: ' + errorMessage);
            }
        });
    }

    function unarchiveOrder(orderId) {
        if (!confirm('Restore this order from archive?')) return;
        
        $.ajax({
            url: '/orders/' + orderId + '/unarchive',
            method: 'PUT',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                alert(response.message);
                location.reload();
            },
            error: function(xhr) {
                console.error('Error response:', xhr.responseJSON);
                let errorMessage = xhr.responseJSON?.error || xhr.responseJSON?.message || 'An error occurred.';
                alert('Error: ' + errorMessage);
            }
        });
    }

    function printReceipt() {
        alert('Receipt printing functionality would be implemented here');
    }
</script>
@endpush