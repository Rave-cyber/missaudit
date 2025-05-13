<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit; // Ensure the package is installed and this class exists

class AuditLogController extends Controller
{
   public function index()
{
    $query = Audit::with('user')->orderBy('created_at', 'desc');
    
    // Get unique model types for filter dropdown
    $models = Audit::distinct('auditable_type')->pluck('auditable_type');
    
    // Apply filters
    if(request('action')) {
        $query->where('event', request('action'));
    }
    
    if(request('model')) {
        $query->where('auditable_type', request('model'));
    }
    
    if(request('date_from')) {
        $query->whereDate('created_at', '>=', request('date_from'));
    }
    
    if(request('date_to')) {
        $query->whereDate('created_at', '<=', request('date_to'));
    }
    
    $audits = $query->paginate(20);
    
    return view('admin.audit', [
        'audits' => $audits,
        'models' => $models
    ]);
}

public function show(Audit $audit)
{
    return response()->json([
        'old_values' => $audit->old_values,
        'new_values' => $audit->new_values
    ]);
}
}