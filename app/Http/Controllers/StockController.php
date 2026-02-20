<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Product;
use App\Models\Provider;
use App\Models\Stock;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('stocks/index', [
            'stocks' => Stock::query()
                ->with(['provider', 'product', 'client'])
                ->latest()
                ->paginate(8),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('stocks/create', [
            'products' => Product::query()
                ->select('id', 'name')
                ->orderBy('name')
                ->get(),
            'providers' => Provider::query()
                ->select('id', 'company_name')
                ->orderBy('company_name')
                ->get(),
            'clients' => Client::query()
                ->select('id', 'name')
                ->orderBy('name')
                ->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'provider_id' => 'nullable|exists:providers,id',
            'client_id' => 'nullable|exists:clients,id',
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'remarks' => 'nullable|string|max:255',
        ]);

        Stock::create($request->all());

        return redirect()->route('stocks.index')->with('success', 'Stock created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Stock $stock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stock $stock)
    {
        return Inertia::render('stocks/edit', [
            'stock' => $stock,
            'products' => Product::query()
                ->select('id', 'name')
                ->orderBy('name')
                ->get(),
            'providers' => Provider::query()
                ->select('id', 'company_name')
                ->orderBy('company_name')
                ->get(),
            'clients' => Client::query()
                ->select('id', 'name')
                ->orderBy('name')
                ->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stock $stock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stock $stock)
    {
        //
    }
}
