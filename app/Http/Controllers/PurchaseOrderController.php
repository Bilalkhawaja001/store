<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseOrderRequest;
use App\Models\Item;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequisition;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PurchaseOrderController extends Controller
{
    public function index(): View
    {
        $purchaseOrders = PurchaseOrder::query()
            ->with(['purchaseRequisition', 'item'])
            ->when(request('search'), function ($query, $search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('po_no', 'like', "%{$search}%")
                        ->orWhere('vendor_name', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%");
                });
            })
            ->latest('po_date')
            ->paginate(10)
            ->withQueryString();

        return view('purchase-orders.index', compact('purchaseOrders'));
    }

    public function create(): View
    {
        return view('purchase-orders.create', [
            'purchaseRequisitions' => PurchaseRequisition::query()->with('item')->orderByDesc('pr_date')->get(),
            'items' => Item::query()->orderBy('name')->get(),
            'statuses' => PurchaseOrder::STATUSES,
        ]);
    }

    public function store(PurchaseOrderRequest $request): RedirectResponse
    {
        $purchaseOrder = PurchaseOrder::create($request->validated());
        $purchaseOrder->purchaseRequisition()->update(['status' => 'Converted to PO']);

        return redirect()->route('purchase-orders.index')->with('success', 'Purchase order created successfully.');
    }

    public function show(PurchaseOrder $purchaseOrder): View
    {
        $purchaseOrder->load(['purchaseRequisition', 'item', 'stockReceipts']);

        return view('purchase-orders.show', compact('purchaseOrder'));
    }

    public function edit(PurchaseOrder $purchaseOrder): View
    {
        return view('purchase-orders.edit', [
            'purchaseOrder' => $purchaseOrder,
            'purchaseRequisitions' => PurchaseRequisition::query()->with('item')->orderByDesc('pr_date')->get(),
            'items' => Item::query()->orderBy('name')->get(),
            'statuses' => PurchaseOrder::STATUSES,
        ]);
    }

    public function update(PurchaseOrderRequest $request, PurchaseOrder $purchaseOrder): RedirectResponse
    {
        $purchaseOrder->update($request->validated());

        return redirect()->route('purchase-orders.index')->with('success', 'Purchase order updated successfully.');
    }

    public function destroy(PurchaseOrder $purchaseOrder): RedirectResponse
    {
        $purchaseOrder->delete();

        return redirect()->route('purchase-orders.index')->with('success', 'Purchase order deleted successfully.');
    }
}
