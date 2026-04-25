<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockIssueRequest;
use App\Models\Category;
use App\Models\Item;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequisition;
use App\Models\StockIssue;
use App\Models\StockReceipt;
use App\Models\Store;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class StockIssueController extends Controller
{
    public function index(): View
    {
        $stockIssues = StockIssue::query()
            ->with(['store', 'category', 'item', 'purchaseRequisition', 'purchaseOrder'])
            ->when(request('search'), function ($query, $search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('issue_no', 'like', "%{$search}%")
                        ->orWhere('issued_to_person', 'like', "%{$search}%")
                        ->orWhere('usage_purpose', 'like', "%{$search}%")
                        ->orWhere('handover_name', 'like', "%{$search}%");
                });
            })
            ->latest('issue_date')
            ->paginate(10)
            ->withQueryString();

        return view('stock-issues.index', compact('stockIssues'));
    }

    public function create(): View
    {
        return view('stock-issues.create', [
            'stores' => Store::query()->orderBy('name')->get(),
            'categories' => Category::query()->orderBy('name')->get(),
            'items' => Item::query()->with('category')->orderBy('name')->get(),
            'purchaseRequisitions' => PurchaseRequisition::query()->orderByDesc('pr_date')->get(),
            'purchaseOrders' => PurchaseOrder::query()->orderByDesc('po_date')->get(),
        ]);
    }

    public function store(StockIssueRequest $request): RedirectResponse
    {
        $availableQty = $this->availableQty(
            $request->integer('store_id'),
            $request->integer('item_id')
        );

        StockIssue::create([
            ...$request->validated(),
            'available_qty' => $availableQty,
        ]);

        return redirect()->route('stock-issues.index')->with('success', 'Stock issue created successfully.');
    }

    public function show(StockIssue $stockIssue): View
    {
        $stockIssue->load(['store', 'category', 'item', 'purchaseRequisition', 'purchaseOrder']);

        return view('stock-issues.show', compact('stockIssue'));
    }

    public function destroy(StockIssue $stockIssue): RedirectResponse
    {
        $stockIssue->delete();

        return redirect()->route('stock-issues.index')->with('success', 'Stock issue deleted successfully.');
    }

    private function availableQty(int $storeId, int $itemId): float
    {
        return (float) StockReceipt::query()
            ->where('store_id', $storeId)
            ->where('item_id', $itemId)
            ->sum('received_qty')
            - (float) StockIssue::query()
                ->where('store_id', $storeId)
                ->where('item_id', $itemId)
                ->sum('issue_qty');
    }
}
