<?php

namespace App\Http\Controllers\Employee;

use App\Models\supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Supplier_Controller extends Controller
{
    public function index()
    {
        $supplier = supplier::all();
        return view('employee.supplier.index', compact('supplier'));
    }

    public function create()
    {
        return view('employee.supplier.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|unique:suppliers',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ]);

        supplier::create($request->all());

        return redirect()->route('employee.supplier.index')->with('success', 'Suppliers added successfully.');

    }

    public function edit(supplier $supplier)
    {
        return view('employee.supplier.edit', compact('supplier'));
    }

    public function update(Request $request, supplier $supplier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|unique:suppliers,email,' . $supplier->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ]);

        $supplier->update($request->all());

        return redirect()->route('employee.supplier.index')->with('success', 'Supplier Updated successfully.');
    }

    public function destroy(supplier $supplier)
    {
        $supplier->delete();

        return redirect()->route('employee.supplier.index')->with('success', 'Supplier deleted successfully.');
    }
}
