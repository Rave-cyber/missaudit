<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $activeOrders = Order::where('is_archived', false)
            ->when($search, function($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('id', 'like', "%$search%")
                      ->orWhere('order_name', 'like', "%$search%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(6);  // Changed from get() to paginate()
                           
        $archivedOrders = Order::where('is_archived', true)
            ->orderBy('updated_at', 'desc')
            ->get();
                             
        return view('orders.index', compact('activeOrders', 'archivedOrders', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_name' => 'required|string|max:255',
            'weight' => 'required|numeric|min:0.1',
            'date' => 'required|date',
            'service_type' => 'required|array',
            'payment_method' => 'required|string',
            'amount' => 'required|numeric',
            'special_instructions' => 'nullable|string',
        ]);

        $order = Order::create([
            'order_name' => $validated['order_name'],
            'weight' => $validated['weight'],
            'date' => $validated['date'],
            'service_type' => $validated['service_type'],
            'status' => 'Pending',
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'pending',
            'amount' => $validated['amount'],
            'special_instructions' => $validated['special_instructions'] ?? null,
            'is_archived' => false,
        ]);
        
        $order->updateStatus('Pending');

        return redirect()->route('orders.index')
            ->with('success', 'Order created successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Washing,Drying,Ironing,Ready,Completed'
        ]);
        
        $order = Order::findOrFail($id);
        $order->updateStatus($request->status);

        return response()->json(['success' => 'Order status updated successfully!']);
    }

    public function markAsPaid(Order $order)
    {
        if ($order->status !== 'Completed') {
            return response()->json([
                'error' => 'Order must be completed before marking as paid.'
            ], 422);
        }

        $order->update([
            'payment_status' => 'paid',
            'is_archived' => true
        ]);

        return response()->json([
            'message' => 'Order marked as paid and archived successfully.'
        ]);
    }

    public function archiveOrder($id)
    {
        $order = Order::findOrFail($id);
        
        if ($order->status !== 'Completed' || $order->payment_status !== 'paid') {
            return response()->json([
                'error' => 'Order must be completed and paid before archiving.'
            ], 422);
        }
        
        $order->update(['is_archived' => true]);
        
        return response()->json(['message' => 'Order archived successfully']);
    }

    public function unarchiveOrder($id)
    {
        $order = Order::findOrFail($id);
        $order->update(['is_archived' => false]);
        
        return response()->json(['message' => 'Order restored from archive']);
    }

    public function show($id)
    {
        try {
            $order = Order::with('statusLogs.user')->findOrFail($id);
            return response()->json($order);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve order: ' . $e->getMessage()], 404);
        }
    }

    protected function generateReceiptContent(Order $order)
    {
        if (is_array($order->service_type)) {
            $services = implode(', ', $order->service_type);
        } else {
            $services = $order->service_type;
        }
        
        $receipt = "================================\n";
        $receipt .= "        LAUNDRY RECEIPT         \n";
        $receipt .= "================================\n";
        $receipt .= "Order ID: #" . $order->id . "\n";
        $receipt .= "Date: " . $order->date . "\n";
        $receipt .= "Customer: " . $order->order_name . "\n";
        $receipt .= "--------------------------------\n";
        $receipt .= "Services: " . $services . "\n";
        $receipt .= "Weight: " . $order->weight . " kg\n";
        $receipt .= "Payment Method: " . $order->payment_method . "\n";
        $receipt .= "Status: " . $order->status . "\n";
        $receipt .= "Payment Status: " . $order->payment_status . "\n";
        
        if ($order->special_instructions) {
            $receipt .= "Special Instructions: " . $order->special_instructions . "\n";
        }
        
        $receipt .= "--------------------------------\n";
        $receipt .= "Total Amount: $" . number_format($order->amount, 2) . "\n";
        $receipt .= "================================\n";
        $receipt .= "Thank you for your business!\n";
        $receipt .= "================================\n";

        return $receipt;
    }
}