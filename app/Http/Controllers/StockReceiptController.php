<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockReceiptRequest;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequisition;
use App\Models\StockReceipt;
use App\Models\Store;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class StockReceiptController extends Controller
{
    public function index(): View
    {
        $stockReceipts = StockReceipt::query()
            ->with(['purchaseOrder', 'purchaseRequisition', 'item', 'store', 'sourceStore'])
            ->when(request('search'), function ($query, $search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('grn_no', 'like', "%{$search}%")
                        ->orWhere('challan_no', 'like', "%{$search}%")
                        ->orWhere('received_by', 'like', "%{$search}%")
                        ->orWhere('handover_to', 'like', "%{$search}%")
                        ->orWhere('acquisition_type', 'like', "%{$search}%")
                        ->orWhere('acquisition_reference', 'like', "%{$search}%")
                        ->orWhere('lender_name', 'like', "%{$search}%")
                        ->orWhere('loan_status', 'like', "%{$search}%");
                });
            })
            ->latest('receive_date')
            ->paginate(10)
            ->withQueryString();

        $purchaseOrders = PurchaseOrder::query()->with(['purchaseRequisition', 'item'])->orderByDesc('po_date')->get();

        $acquisitionSummary = StockReceipt::query()
            ->select([
                DB::raw("COALESCE(acquisition_type, 'Unspecified') as acquisition_type"),
                DB::raw('COUNT(*) as receipt_count'),
                DB::raw('SUM(received_qty) as total_received_qty'),
                DB::raw('SUM(CASE WHEN lender_name IS NOT NULL THEN 1 ELSE 0 END) as loan_receipt_count'),
                DB::raw('MAX(receive_date) as latest_receive_date'),
            ])
            ->groupBy('acquisition_type')
            ->orderBy('acquisition_type')
            ->get();

        return view('stock-receipts.index', [
            'stockReceipts' => $stockReceipts,
            'purchaseOrders' => $purchaseOrders,
            'purchaseRequisitions' => PurchaseRequisition::query()->orderByDesc('pr_date')->get(),
            'stores' => Store::query()->orderBy('name')->get(),
            'acquisitionTypes' => StockReceipt::ACQUISITION_TYPES,
            'loanStatuses' => StockReceipt::LOAN_STATUSES,
            'acquisitionSummary' => $acquisitionSummary,
        ]);
    }

    public function create(): View
    {
        $purchaseOrders = PurchaseOrder::query()->with(['purchaseRequisition', 'item'])->orderByDesc('po_date')->get();

        return view('stock-receipts.create', [
            'purchaseOrders' => $purchaseOrders,
            'purchaseRequisitions' => PurchaseRequisition::query()->orderByDesc('pr_date')->get(),
            'stores' => Store::query()->orderBy('name')->get(),
            'acquisitionTypes' => StockReceipt::ACQUISITION_TYPES,
            'loanStatuses' => StockReceipt::LOAN_STATUSES,
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
        $stockReceipt->load(['purchaseOrder', 'purchaseRequisition', 'item', 'store', 'sourceStore']);

        return view('stock-receipts.show', compact('stockReceipt'));
    }

    public function destroy(StockReceipt $stockReceipt): RedirectResponse
    {
        $stockReceipt->delete();

        return redirect()->route('stock-receipts.index')->with('success', 'Stock receipt deleted successfully.');
    }
}
