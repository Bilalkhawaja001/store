<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockReceiptRequest;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequisition;
use App\Models\StockReceipt;
use App\Models\Store;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class StockReceiptController extends Controller
{
    public function index(): View
    {
        $stockReceipts = StockReceipt::query()
            ->with(['purchaseOrder', 'purchaseRequisition', 'item', 'store'])
            ->when(request('search'), function ($query, $search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('grn_no', 'like', "%{$search}%")
                        ->orWhere('challan_no', 'like', "%{$search}%")
                        ->orWhere('received_by', 'like', "%{$search}%")
                        ->orWhere('handover_to', 'like', "%{$search}%");
                });
            })
            ->latest('receive_date')
            ->paginate(10)
            ->withQueryString();

        return view('stock-receipts.index', compact('stockReceipts'));
    }

    public function create(): View
    {
        $purchaseOrders = PurchaseOrder::query()->with(['purchaseRequisition', 'item'])->orderByDesc('po_date')->get();

        return view('stock-receipts.create', [
            'purchaseOrders' => $purchaseOrders,
            'purchaseRequisitions' => PurchaseRequisition::query()->orderByDesc('pr_date')->get(),
            'stores' => Store::query()->orderBy('name')->get(),
        ]);
    }

    public function store(StockReceiptRequest $request): RedirectResponse
    {
        $purchaseOrder = PurchaseOrder::findOrFail($request->integer('purchase_order_id'));
        $alreadyReceivedQty = $purchaseOrder->totalReceivedQty();
        $receivedQty = (float) $request->input('received_qty');

        StockReceipt::create([
            ...$request->validated(),
            'ordered_qty' => $purchaseOrder->ordered_qty,
            'already_received_qty' => $alreadyReceivedQty,
            'pending_qty' => max((float) $purchaseOrder->ordered_qty - ($alreadyReceivedQty + $receivedQty), 0),
        ]);

        return redirect()->route('stock-receipts.index')->with('success', 'Stock receipt created successfully.');
    }

    public function show(StockReceipt $stockReceipt): View
    {
        $stockReceipt->load(['purchaseOrder', 'purchaseRequisition', 'item', 'store']);

        return view('stock-receipts.show', compact('stockReceipt'));
    }

    public function destroy(StockReceipt $stockReceipt): RedirectResponse
    {
        $stockReceipt->delete();

        return redirect()->route('stock-receipts.index')->with('success', 'Stock receipt deleted successfully.');
    }
}
