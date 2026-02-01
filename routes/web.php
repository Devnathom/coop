<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\FiscalYearController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    Route::get('/api/sales-chart', [DashboardController::class, 'salesChart'])->name('api.sales-chart');

    // Members
    Route::get('/members/search', [MemberController::class, 'search'])->name('members.search');
    Route::get('/members/promote', [MemberController::class, 'promoteIndex'])->name('members.promote');
    Route::post('/members/promote-all', [MemberController::class, 'promoteAll'])->name('members.promote-all');
    Route::post('/members/graduate', [MemberController::class, 'graduate'])->name('members.graduate');
    Route::post('/members/{member}/promote', [MemberController::class, 'promote'])->name('members.promote-one');
    Route::resource('members', MemberController::class);

    // Categories
    Route::resource('categories', CategoryController::class)->except(['show']);

    // Products
    Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
    Route::get('/products/barcode', [ProductController::class, 'getByBarcode'])->name('products.barcode');
    Route::resource('products', ProductController::class);

    // Stock
    Route::get('/stocks/low', [StockController::class, 'lowStock'])->name('stocks.low');
    Route::resource('stocks', StockController::class)->only(['index', 'create', 'store']);

    // Sales
    Route::get('/sales/{sale}/receipt', [SaleController::class, 'receipt'])->name('sales.receipt');
    Route::resource('sales', SaleController::class)->only(['index', 'create', 'store', 'show']);

    // Fiscal Years & Dividends
    Route::post('/fiscal-years/{fiscalYear}/calculate', [FiscalYearController::class, 'calculate'])->name('fiscal-years.calculate');
    Route::post('/fiscal-years/{fiscalYear}/dividends', [FiscalYearController::class, 'calculateDividends'])->name('fiscal-years.dividends');
    Route::post('/dividends/{dividend}/pay', [FiscalYearController::class, 'payDividend'])->name('dividends.pay');
    Route::post('/patronage-refunds/{patronageRefund}/pay', [FiscalYearController::class, 'payPatronageRefund'])->name('patronage-refunds.pay');
    Route::resource('fiscal-years', FiscalYearController::class)->only(['index', 'create', 'store', 'show']);

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
        Route::get('/products', [ReportController::class, 'products'])->name('products');
        Route::get('/members', [ReportController::class, 'members'])->name('members');
        Route::get('/profit', [ReportController::class, 'profit'])->name('profit');
        Route::get('/stock', [ReportController::class, 'stock'])->name('stock');
    });

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/remove-logo', [SettingController::class, 'removeLogo'])->name('settings.remove-logo');
});
