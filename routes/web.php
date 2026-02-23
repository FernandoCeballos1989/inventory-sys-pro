<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\StockController;
use App\Models\Client;
use App\Models\Product;
use App\Models\Provider;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::group(['middleware' => ['auth', 'verified']], function () {

    Route::get('dashboard', function () {
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();

        $totalProducts = Product::count();
        $totalProviders = Provider::count();
        $totalClients = Client::count();
        $lowStockProducts = Product::query()
            ->whereColumn('current_stock', '<=', 'min_stock');

        $lowStockCount = (clone $lowStockProducts)->count();
        $inventoryUnits = (int) Product::sum('current_stock');
        $inventoryValue = (float) Product::query()
            ->selectRaw('COALESCE(SUM(current_stock * selling_price), 0) as total')
            ->value('total');

        $incomingUnitsMonth = (int) Stock::query()
            ->where('type', 'in')
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->sum('quantity');

        $outgoingUnitsMonth = (int) Stock::query()
            ->where('type', 'out')
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->sum('quantity');

        $topMovedProducts = Stock::query()
            ->selectRaw('product_id, SUM(quantity) as total_quantity')
            ->with('product:id,sku,name,current_stock,min_stock')
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->product?->id,
                    'sku' => $item->product?->sku ?? 'N/A',
                    'name' => $item->product?->name ?? 'Unknown',
                    'current_stock' => $item->product?->current_stock ?? 0,
                    'min_stock' => $item->product?->min_stock ?? 0,
                    'moved_units' => (int) $item->total_quantity,
                ];
            });

        $lowStockList = $lowStockProducts
            ->select('id', 'sku', 'name', 'current_stock', 'min_stock')
            ->orderByRaw('(min_stock - current_stock) DESC')
            ->limit(6)
            ->get();

        return Inertia::render('Dashboard', [
            'kpis' => [
                'total_products' => $totalProducts,
                'total_providers' => $totalProviders,
                'total_clients' => $totalClients,
                'low_stock_count' => $lowStockCount,
                'inventory_units' => $inventoryUnits,
                'inventory_value' => $inventoryValue,
                'incoming_units_month' => $incomingUnitsMonth,
                'outgoing_units_month' => $outgoingUnitsMonth,
                'period' => [
                    'start' => $monthStart->toDateString(),
                    'end' => $monthEnd->toDateString(),
                ],
            ],
            'top_moved_products' => $topMovedProducts,
            'low_stock_products' => $lowStockList,
        ]);
    })->name('dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('clients', ClientController::class);
    Route::resource('providers', ProviderController::class);
    Route::resource('products', ProductController::class);
    Route::resource('stocks', StockController::class);
});

require __DIR__ . '/settings.php';
