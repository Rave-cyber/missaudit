<?php

namespace App\Http\Controllers;

use App\Models\ServicePrice;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;
use App\Models\Order; // Import the Order model

class TransactionController extends Controller
{
    public function index(): View
    {
        $prices = ServicePrice::all()->keyBy('service_name');
        return view('transactions.index', compact('prices'));
    }

    public function create(): View
    {
        $prices = ServicePrice::all()->keyBy('service_name');
        return view('transactions.create', compact('prices'));
    }

   
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'weight' => 'required|numeric|min:0.1',
            'order_date' => 'required|date',
            'services' => 'required|array|min:1',
            'services.*' => 'in:Wash,Fold,Ironing',
            'payment_method' => 'required|in:Cash,Card,Mobile',
            'amount' => 'required|numeric|min:0',
            'special_instructions' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $servicePrices = ServicePrice::all()->keyBy('service_name');
            
            // Create as Order instead of Transaction
            $order = Order::create([
                'order_name' => $validated['customer_name'],
                'weight' => $validated['weight'],
                'date' => $validated['order_date'],
                'service_type' => $validated['services'],
                'status' => 'Pending',
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'pending',
                'amount' => $validated['amount'],
                'special_instructions' => $validated['special_instructions'],
                'is_archived' => false,
                'pricing_details' => [
                    'Wash' => $servicePrices['Wash'] ?? null,
                    'Fold' => $servicePrices['Fold'] ?? null,
                    'Ironing' => $servicePrices['Ironing'] ?? null
                ]
            ]);

            $order->updateStatus('Pending');
            
            DB::commit();
            
            return redirect()->route('orders.index')  // Changed to redirect to orders
                ->with('success', 'Order created successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed: '.$e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);
            return back()->with('error', 'Failed to create order: '.$e->getMessage())->withInput();
        }
    }
    public function updatePrices(Request $request)
    {
        $validated = $request->validate([
            'wash_price' => 'required|numeric|min:0',
            'wash_limit' => 'required|numeric|min:0',
            'wash_extra' => 'required|numeric|min:0',
            'fold_price' => 'required|numeric|min:0',
            'fold_limit' => 'required|numeric|min:0',
            'fold_extra' => 'required|numeric|min:0',
            'ironing_price' => 'required|numeric|min:0',
            'ironing_limit' => 'required|numeric|min:0',
            'ironing_extra' => 'required|numeric|min:0'
        ]);

        DB::beginTransaction();
        try {
            // Update or create services
            $services = [
                'Wash' => [
                    'base_price' => $validated['wash_price'],
                    'weight_limit' => $validated['wash_limit'],
                    'extra_rate' => $validated['wash_extra']
                ],
                'Fold' => [
                    'base_price' => $validated['fold_price'],
                    'weight_limit' => $validated['fold_limit'],
                    'extra_rate' => $validated['fold_extra']
                ],
                'Ironing' => [
                    'base_price' => $validated['ironing_price'],
                    'weight_limit' => $validated['ironing_limit'],
                    'extra_rate' => $validated['ironing_extra']
                ]
            ];

            foreach ($services as $serviceName => $priceData) {
                ServicePrice::updateOrCreate(
                    ['service_name' => $serviceName],
                    $priceData
                );
            }

            DB::commit();
            
            Log::info('Service prices updated', $validated);
            return back()->with('success', 'Prices updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Price update failed: '.$e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);
            return back()->with('error', 'Failed to update prices: '.$e->getMessage());
        }
    }

    public function getServicePrices(): JsonResponse
    {
        try {
            $prices = ServicePrice::all()->keyBy('service_name');
            
            $response = [
                'Wash' => $prices['Wash'] ?? null,
                'Fold' => $prices['Fold'] ?? null,
                'Ironing' => $prices['Ironing'] ?? null
            ];

            Log::info('Service prices fetched', $response);
            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Failed to fetch service prices: '.$e->getMessage(), [
                'exception' => $e
            ]);
            return response()->json([
                'error' => 'Failed to fetch prices',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}