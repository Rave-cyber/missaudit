<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\ServicePriceController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Inventory\StockController;
use App\Http\Controllers\Admin\SalesReportController;
use App\Http\Controllers\Employee\SupplierController as EmployeeSupplierController;
use App\Http\Controllers\Inventory\ReceiveOrderController;
use App\Http\Controllers\Employee\ItemsController;
use App\Http\Controllers\OrderTrackingController;
use App\Http\Controllers\Admin\AuditLogController;

// Public Routes
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/track-order', [OrderTrackingController::class, 'trackOrder'])->name('track.order');

Route::middleware('auth')->group(function () {
    // Dashboard and Profile
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // Orders
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::put('/{order}/status', [OrderController::class, 'updateStatus'])->name('status.update');
        Route::put('/{order}/mark-paid', [OrderController::class, 'markAsPaid'])->name('mark-paid');
        Route::put('/{order}/archive', [OrderController::class, 'archiveOrder'])->name('archive');
        Route::put('/{order}/unarchive', [OrderController::class, 'unarchiveOrder'])->name('unarchive');
        Route::get('/{id}/tracking', [OrderController::class, 'tracking'])->name('tracking');
    });
    
    // Transactions
     Route::resource('transactions', TransactionController::class);
});

    Route::get('/api/service-prices', [TransactionController::class, 'getServicePrices'])->name('api.service-prices');


// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function() {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Price Update Route 
    Route::put('/prices/update', [TransactionController::class, 'updatePrices'])
        ->name('prices.update');
    
    // Service Prices
    Route::prefix('service-prices')->name('service-prices.')->group(function() {
        Route::get('/', [ServicePriceController::class, 'index'])->name('index');
        Route::get('/json', [ServicePriceController::class, 'getJson'])->name('json');

    Route::get('/admin/audit', [AuditLogController::class, 'index'])->name('admin.audit.index');
    Route::get('/admin/audit/{audit}/details', [AuditLogController::class, 'show'])->name('admin.audit.show');
    });

    // Resources
    Route::resource('employees', EmployeeController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('inventory', InventoryController::class);

    // Sales Reports
    Route::prefix('sales-reports')->name('sales-reports.')->group(function () {
        Route::get('/', [SalesReportController::class, 'index'])->name('index');
        Route::post('/generate', [SalesReportController::class, 'generate'])->name('generate');
        Route::post('/export/excel', [SalesReportController::class, 'exportExcel'])->name('export.excel');
        Route::post('/export/pdf', [SalesReportController::class, 'exportPDF'])->name('export.pdf');
    });
});

// Employee Routes
Route::middleware(['auth'])->prefix('employee')->name('employee.')->group(function () {
    // Resources
    Route::resource('suppliers', EmployeeSupplierController::class);
    Route::resource('inventory-items', ItemsController::class);
    
    // Stock Management
    Route::prefix('stock')->group(function () {
        // Stock In
        Route::get('/in', [StockController::class, 'stockInForm'])->name('stock-in.form');
        Route::post('/in', [StockController::class, 'stockIn'])->name('stock-in');
        Route::get('/in/history', [StockController::class, 'index'])->name('stock-in.index');
        
        // Receive Orders
        Route::get('/in/purchase-order/{id}', [StockController::class, 'stockInFromReceiveOrderForm'])->name('stock-in.from-ro');
        Route::post('/in/purchase-order/{id}', [StockController::class, 'stockInFromReceiveOrderSubmit'])->name('stock-in.from-ro.submit');
        
        // Stock Out
        Route::get('/out/create', [StockController::class, 'stockOutForm'])->name('stock-out.form');
        Route::post('/out', [StockController::class, 'stockOutSubmit'])->name('stock-out');
        Route::get('/out/history', [StockController::class, 'stockOutIndex'])->name('stock-out.index');
    });
    
    // Receive Orders
    Route::resource('receive-orders', ReceiveOrderController::class);
});

// Authentication Routes
require __DIR__.'/auth.php';