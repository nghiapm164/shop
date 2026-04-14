<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFlashSaleRequest;
use App\Http\Requests\UpdateFlashSaleRequest;
use App\Models\FlashSale;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class AdminFlashSaleController extends Controller
{
    public function index(): View
    {
        $flashSales = FlashSale::with('product')
            ->latest('id')
            ->paginate(15);

        return view('admin.flash-sales.index', compact('flashSales'));
    }

    public function create(): View
    {
        return view('admin.flash-sales.create', [
            'flashSale' => new FlashSale(),
            'products' => $this->productsForSelect(),
        ]);
    }

    public function store(StoreFlashSaleRequest $request): RedirectResponse
    {
        FlashSale::create($request->validated());

        return redirect()->route('admin.flash-sales.index')->with('success', 'Flash sale created successfully.');
    }

    public function edit(FlashSale $flashSale): View
    {
        return view('admin.flash-sales.edit', [
            'flashSale' => $flashSale,
            'products' => $this->productsForSelect(),
        ]);
    }

    public function update(UpdateFlashSaleRequest $request, FlashSale $flashSale): RedirectResponse
    {
        $flashSale->update($request->validated());

        return redirect()->route('admin.flash-sales.index')->with('success', 'Flash sale updated successfully.');
    }

    public function destroy(FlashSale $flashSale): RedirectResponse
    {
        $flashSale->delete();

        return redirect()->route('admin.flash-sales.index')->with('success', 'Flash sale deleted successfully.');
    }

    private function productsForSelect()
    {
        return Product::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'sku', 'price', 'sale_price']);
    }
}
