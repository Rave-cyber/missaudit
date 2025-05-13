<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ServicePrice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
{
    // Default to last 30 days if no date range is specified
    $endDate = Carbon::today();
    $startDate = Carbon::today()->subDays(30);

    // Apply filters if they exist in the request
    if ($request->has('start_date') && $request->has('end_date')) {
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
    }

    // Get filtered orders (without the undefined 'services' relationship)
    $orders = Order::whereBetween('created_at', [$startDate, $endDate])->get();

    // Calculate totals
    $totals = [
        'Wash' => $this->calculateServiceTotal($orders, 'Wash'),
        'Fold' => $this->calculateServiceTotal($orders, 'Fold'),
        'Ironing' => $this->calculateServiceTotal($orders, 'Ironing'),
        'All' => $orders->sum('amount'),
    ];

    // Get service prices
    $servicePrices = [
        'Wash' => ServicePrice::firstOrCreate(
            ['service_name' => 'Wash'],
            ['base_price' => 50, 'weight_limit' => 5, 'extra_rate' => 60]
        ),
        'Fold' => ServicePrice::firstOrCreate(
            ['service_name' => 'Fold'],
            ['base_price' => 30, 'weight_limit' => 7, 'extra_rate' => 40]
        ),
        'Ironing' => ServicePrice::firstOrCreate(
            ['service_name' => 'Ironing'],
            ['base_price' => 40, 'weight_limit' => 6, 'extra_rate' => 50]
        )
    ];

    return view('admin.dashboard', [
        'orders' => $orders,
        'totals' => $totals,
        'chartData' => [
            'pieChart' => $this->getPieChartData($orders),
            'lineChart' => $this->getLineChartData($startDate, $endDate)
        ],
        'startDate' => $startDate,
        'endDate' => $endDate,
        'servicePrices' => $servicePrices
    ]);
}
    protected function calculateServiceTotal($orders, $serviceType)
    {
        return $orders->filter(function ($order) use ($serviceType) {
            // Handle both array service_type and string service_type
            if (is_array($order->service_type)) {
                return in_array($serviceType, $order->service_type);
            }
            return $order->service_type === $serviceType;
        })->sum('amount');
    }

    protected function getPieChartData($orders)
    {
        return [
            'labels' => ['Wash', 'Fold', 'Ironing'],
            'data' => [
                $this->calculateServiceTotal($orders, 'Wash'),
                $this->calculateServiceTotal($orders, 'Fold'),
                $this->calculateServiceTotal($orders, 'Ironing')
            ],
            'colors' => [
                'rgba(23, 232, 255, 0.7)',
                'rgba(7, 156, 214, 0.7)',
                'rgba(36, 89, 188, 0.7)'
            ]
        ];
    }

    protected function getLineChartData($startDate, $endDate)
    {
        $daysDifference = $startDate->diffInDays($endDate);
        
        return match(true) {
            $daysDifference <= 7 => $this->getDailySalesData($startDate, $endDate),
            $daysDifference <= 30 => $this->getWeeklySalesData($startDate, $endDate),
            default => $this->getMonthlySalesData($startDate, $endDate)
        };
    }

    protected function getDailySalesData($startDate, $endDate)
    {
        $data = [];
        $currentDate = $startDate->copy();
        
        while ($currentDate <= $endDate) {
            $data[$currentDate->format('M j')] = Order::whereDate('created_at', $currentDate)->sum('amount');
            $currentDate->addDay();
        }
        
        return [
            'labels' => array_keys($data),
            'data' => array_values($data),
            'color' => 'rgba(23, 232, 255, 1)'
        ];
    }

    protected function getWeeklySalesData($startDate, $endDate)
    {
        $data = [];
        $currentDate = $startDate->copy();
        
        while ($currentDate <= $endDate) {
            $weekEndDate = (clone $currentDate)->addDays(6);
            $weekEndDate = $weekEndDate > $endDate ? $endDate : $weekEndDate;
            
            $label = $currentDate->format('M j') . ' - ' . $weekEndDate->format('M j');
            $data[$label] = Order::whereBetween('created_at', [$currentDate, $weekEndDate])->sum('amount');
            
            $currentDate->addDays(7);
        }
        
        return [
            'labels' => array_keys($data),
            'data' => array_values($data),
            'color' => 'rgba(7, 156, 214, 1)'
        ];
    }

    protected function getMonthlySalesData($startDate, $endDate)
    {
        $data = [];
        $currentDate = $startDate->copy()->startOfMonth();
        
        while ($currentDate <= $endDate) {
            $monthEndDate = (clone $currentDate)->endOfMonth();
            $monthEndDate = $monthEndDate > $endDate ? $endDate : $monthEndDate;
            
            $data[$currentDate->format('M Y')] = Order::whereBetween('created_at', [$currentDate, $monthEndDate])->sum('amount');
            $currentDate->addMonth()->startOfMonth();
        }
        
        return [
            'labels' => array_keys($data),
            'data' => array_values($data),
            'color' => 'rgba(36, 89, 188, 1)'
        ];
    }

   public function updatePrices(Request $request)
{
    \Log::info('Update Prices Form Submitted', $request->all());
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
        // Update Wash service
        ServicePrice::updateOrCreate(
            ['service_name' => 'Wash'],
            [
                'base_price' => $validated['wash_price'],
                'weight_limit' => $validated['wash_limit'],
                'extra_rate' => $validated['wash_extra']
            ]
        );

        // Update Fold service
        ServicePrice::updateOrCreate(
            ['service_name' => 'Fold'],
            [
                'base_price' => $validated['fold_price'],
                'weight_limit' => $validated['fold_limit'],
                'extra_rate' => $validated['fold_extra']
            ]
        );

        // Update Ironing service
        ServicePrice::updateOrCreate(
            ['service_name' => 'Ironing'],
            [
                'base_price' => $validated['ironing_price'],
                'weight_limit' => $validated['ironing_limit'],
                'extra_rate' => $validated['ironing_extra']
            ]
        );
        
        DB::commit();

        return back()->with('success', 'Prices updated successfully!');

    } catch (\Exception $e) {
        return back()->with('error', 'Failed to update prices: '.$e->getMessage());
    }
}
// Helper method to update individual services
protected function updateServicePrice($serviceName, $basePrice, $weightLimit, $extraRate)
{
    ServicePrice::updateOrCreate(
        ['service_name' => $serviceName],
        [
            'base_price' => $basePrice,
            'weight_limit' => $weightLimit,
            'extra_rate' => $extraRate
        ]
    );
}
    public function getPricesJson()
    {
        return response()->json(
            ServicePrice::all()->keyBy('service_name')
        );
    }
}