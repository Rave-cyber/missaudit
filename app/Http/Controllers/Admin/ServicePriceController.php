<?php

// app/Http/Controllers/Admin/ServicePriceController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServicePrice;
use Illuminate\Http\Request;

class ServicePriceController extends Controller
{
    public function index()
    {
        $prices = ServicePrice::all();
        return view('admin.service-prices.index', compact('prices'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'prices.*.id' => 'required|exists:service_prices,id',
            'prices.*.service_name' => 'required|string|max:255',
            'prices.*.base_price' => 'required|numeric|min:0',
            'prices.*.weight_limit' => 'required|numeric|min:0',
            'prices.*.extra_rate' => 'required|numeric|min:0',
        ]);

        foreach ($validated['prices'] as $priceData) {
            $price = ServicePrice::findOrFail($priceData['id']);
            $price->update($priceData);
        }

        return back()->with('success', 'Prices updated successfully');
    }

    public function getJson()
    {
        $prices = ServicePrice::all();
        return response()->json($prices);
    }
}