@extends('layouts.admin')

@section('content')
<div class="audit-container">
    <div class="header">
        <h2>Audit Log</h2>
        <div class="actions">
            <form method="GET" action="{{ route('admin.audit.index') }}" class="filter-form">
                <div class="filter-group">
                    <label for="action">Action:</label>
                    <select name="action" id="action">
                        <option value="">All Actions</option>
                        <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Created</option>
                        <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>Updated</option>
                        <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="model">Model:</label>
                    <select name="model" id="model">
                        <option value="">All Models</option>
                        @foreach($models as $model)
                            <option value="{{ $model }}" {{ request('model') == $model ? 'selected' : '' }}>
                                {{ class_basename($model) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="date_from">From:</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}">
                </div>
                
                <div class="filter-group">
                    <label for="date_to">To:</label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}">
                </div>
                
                <button type="submit" class="filter-btn">Filter</button>
                <a href="{{ route('admin.audit.index') }}" class="reset-btn">Reset</a>
            </form>
        </div>
    </div>
    
    <div class="audit-log">
        <table>
            <thead>
                <tr>
                    <th>Date/Time</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Model</th>
                    <th>Changes</th>
                </tr>
            </thead>
            <tbody>
                @forelse($audits as $audit)
                <tr>
                    <td>{{ $audit->created_at->format('Y-m-d H:i:s') }}</td>
                    <td>
                        @if($audit->user)
                            User #{{ $audit->user->id }}
                        @else
                            System
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-{{ $audit->event == 'created' ? 'success' : ($audit->event == 'updated' ? 'warning' : 'danger') }}">
                            {{ ucfirst($audit->event) }}
                        </span>
                    </td>
                    <td>{{ class_basename($audit->auditable_type) }} #{{ $audit->auditable_id }}</td>
                    <td>
                        <button class="view-details" data-audit-id="{{ $audit->id }}">View Details</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">No audit records found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="pagination">
            {{ $audits->links() }}
        </div>
    </div>
    
    <!-- Modal for details -->
    <div class="modal fade" id="auditDetailsModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Audit Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Old Values</h6>
                            <pre id="old-values"></pre>
                        </div>
                        <div class="col-md-6">
                            <h6>New Values</h6>
                            <pre id="new-values"></pre>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .audit-container {
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }
    
    .filter-form {
        display: flex;
        gap: 15px;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .filter-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .filter-btn, .reset-btn {
        padding: 8px 15px;
        border-radius: 4px;
        cursor: pointer;
    }
    
    .filter-btn {
        background-color: #079CD6;
        color: white;
        border: none;
    }
    
    .reset-btn {
        background-color: #f8f9fa;
        color: #333;
        border: 1px solid #ddd;
        text-decoration: none;
    }
    
    .audit-log table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .audit-log th, .audit-log td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }
    
    .audit-log th {
        background-color: #f8f9fa;
        font-weight: 600;
    }
    
    .badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
    }
    
    .badge-success { background-color: #d4edda; color: #155724; }
    .badge-warning { background-color: #fff3cd; color: #856404; }
    .badge-danger { background-color: #f8d7da; color: #721c24; }
    
    .view-details {
        background-color: #079CD6;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 4px;
        cursor: pointer;
    }
    
    pre {
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 4px;
        max-height: 300px;
        overflow-y: auto;
    }
    
    .pagination {
        margin-top: 20px;
        display: flex;
        justify-content: center;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle view details button clicks
        document.querySelectorAll('.view-details').forEach(button => {
            button.addEventListener('click', function() {
                const auditId = this.getAttribute('data-audit-id');
                
                // Fetch audit details via AJAX
                fetch(`/admin/audit/${auditId}/details`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('old-values').textContent = 
                            JSON.stringify(data.old_values, null, 2);
                        document.getElementById('new-values').textContent = 
                            JSON.stringify(data.new_values, null, 2);
                        $('#auditDetailsModal').modal('show');
                    });
            });
        });
    });
</script>
@endsection