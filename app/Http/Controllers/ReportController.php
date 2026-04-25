<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequisition;
use App\Models\StockIssue;
use App\Models\StockReceipt;
use App\Models\Store;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(): View
    {
        $filters = request()->all();

        $categories = Category::orderBy('name')->get();
        $items = Item::orderBy('name')->get();
        $stores = Store::orderBy('name')->get();

        $purchaseRequisitions = PurchaseRequisition::query()
            ->with(['category', 'item'])
            ->when(request('date_from'), fn ($query, $value) => $query->whereDate('pr_date', '>=', $value))
            ->when(request('date_to'), fn ($query, $value) => $query->whereDate('pr_date', '<=', $value))
            ->when(request('category_id'), fn ($query, $value) => $query->where('category_id', $value))
            ->when(request('item_id'), fn ($query, $value) => $query->where('item_id', $value))
            ->when(request('pr_no'), fn ($query, $value) => $query->where('pr_no', 'like', "%{$value}%"))
            ->when(request('purpose'), fn ($query, $value) => $query->where('purpose', 'like', "%{$value}%"))
            ->when(request('status'), fn ($query, $value) => $query->where('status', $value))
            ->latest('pr_date')
            ->get();

        $purchaseOrders = PurchaseOrder::query()
            ->with(['purchaseRequisition', 'item'])
            ->when(request('date_from'), fn ($query, $value) => $query->whereDate('po_date', '>=', $value))
            ->when(request('date_to'), fn ($query, $value) => $query->whereDate('po_date', '<=', $value))
            ->when(request('item_id'), fn ($query, $value) => $query->where('item_id', $value))
            ->when(request('po_no'), fn ($query, $value) => $query->where('po_no', 'like', "%{$value}%"))
            ->when(request('status'), fn ($query, $value) => $query->where('status', $value))
            ->latest('po_date')
            ->get();

        $stockReceipts = StockReceipt::query()
            ->with(['purchaseOrder', 'purchaseRequisition', 'item', 'store'])
            ->when(request('date_from'), fn ($query, $value) => $query->whereDate('receive_date', '>=', $value))
            ->when(request('date_to'), fn ($query, $value) => $query->whereDate('receive_date', '<=', $value))
            ->when(request('item_id'), fn ($query, $value) => $query->where('item_id', $value))
            ->when(request('store_id'), fn ($query, $value) => $query->where('store_id', $value))
            ->when(request('pr_no'), fn ($query, $value) => $query->whereHas('purchaseRequisition', fn ($q) => $q->where('pr_no', 'like', "%{$value}%")))
            ->when(request('po_no'), fn ($query, $value) => $query->whereHas('purchaseOrder', fn ($q) => $q->where('po_no', 'like', "%{$value}%")))
            ->latest('receive_date')
            ->get();

        $stockIssues = StockIssue::query()
            ->with(['store', 'category', 'item', 'purchaseRequisition', 'purchaseOrder'])
            ->when(request('date_from'), fn ($query, $value) => $query->whereDate('issue_date', '>=', $value))
            ->when(request('date_to'), fn ($query, $value) => $query->whereDate('issue_date', '<=', $value))
            ->when(request('category_id'), fn ($query, $value) => $query->where('category_id', $value))
            ->when(request('item_id'), fn ($query, $value) => $query->where('item_id', $value))
            ->when(request('store_id'), fn ($query, $value) => $query->where('store_id', $value))
            ->when(request('person_name'), fn ($query, $value) => $query->where('issued_to_person', 'like', "%{$value}%"))
            ->when(request('purpose'), fn ($query, $value) => $query->where('usage_purpose', 'like', "%{$value}%"))
            ->latest('issue_date')
            ->get();

        $storeBalances = DB::table('stock_receipts')
            ->join('items', 'stock_receipts.item_id', '=', 'items.id')
            ->join('categories', 'items.category_id', '=', 'categories.id')
            ->join('stores', 'stock_receipts.store_id', '=', 'stores.id')
            ->leftJoin('stock_issues', function ($join) {
                $join->on('stock_receipts.item_id', '=', 'stock_issues.item_id')
                    ->on('stock_receipts.store_id', '=', 'stock_issues.store_id');
            })
            ->leftJoin('purchase_requisitions', 'stock_receipts.purchase_requisition_id', '=', 'purchase_requisitions.id')
            ->leftJoin('purchase_orders', 'stock_receipts.purchase_order_id', '=', 'purchase_orders.id')
            ->when(request('category_id'), fn ($query, $value) => $query->where('categories.id', $value))
            ->when(request('item_id'), fn ($query, $value) => $query->where('items.id', $value))
            ->when(request('store_id'), fn ($query, $value) => $query->where('stores.id', $value))
            ->when(request('pr_no'), fn ($query, $value) => $query->where('purchase_requisitions.pr_no', 'like', "%{$value}%"))
            ->when(request('po_no'), fn ($query, $value) => $query->where('purchase_orders.po_no', 'like', "%{$value}%"))
            ->groupBy('categories.name', 'items.name', 'items.item_code', 'stores.name', 'purchase_requisitions.pr_no', 'purchase_orders.po_no')
            ->select([
                'categories.name as category_name',
                'items.name as item_name',
                'items.item_code',
                'stores.name as store_name',
                'purchase_requisitions.pr_no',
                'purchase_orders.po_no',
                DB::raw('SUM(stock_receipts.received_qty) as total_received'),
                DB::raw('COALESCE(SUM(stock_issues.issue_qty),0) as total_issued'),
                DB::raw('SUM(stock_receipts.received_qty) - COALESCE(SUM(stock_issues.issue_qty),0) as balance_qty'),
            ])
            ->orderBy('categories.name')
            ->orderBy('items.name')
            ->get();

        $itemLedger = DB::table('stock_receipts')
            ->join('items', 'stock_receipts.item_id', '=', 'items.id')
            ->join('stores', 'stock_receipts.store_id', '=', 'stores.id')
            ->leftJoin('stock_issues', function ($join) {
                $join->on('stock_receipts.item_id', '=', 'stock_issues.item_id')
                    ->on('stock_receipts.store_id', '=', 'stock_issues.store_id');
            })
            ->when(request('item_id'), fn ($query, $value) => $query->where('items.id', $value))
            ->select([
                'items.name as item_name',
                'items.item_code',
                'stores.name as store_name',
                DB::raw('SUM(stock_receipts.received_qty) as total_received'),
                DB::raw('COALESCE(SUM(stock_issues.issue_qty),0) as total_issued'),
                DB::raw('SUM(stock_receipts.received_qty) - COALESCE(SUM(stock_issues.issue_qty),0) as balance_qty'),
            ])
            ->groupBy('items.name', 'items.item_code', 'stores.name')
            ->orderBy('items.name')
            ->get();

        $personWise = StockIssue::query()
            ->select('issued_to_person', DB::raw('SUM(issue_qty) as total_issue_qty'), DB::raw('COUNT(*) as total_transactions'))
            ->when(request('person_name'), fn ($query, $value) => $query->where('issued_to_person', 'like', "%{$value}%"))
            ->groupBy('issued_to_person')
            ->orderBy('issued_to_person')
            ->get();

        $purposeWise = StockIssue::query()
            ->select('usage_purpose', DB::raw('SUM(issue_qty) as total_issue_qty'), DB::raw('COUNT(*) as total_transactions'))
            ->when(request('purpose'), fn ($query, $value) => $query->where('usage_purpose', 'like', "%{$value}%"))
            ->groupBy('usage_purpose')
            ->orderBy('usage_purpose')
            ->get();

        $categoryWise = DB::table('stock_receipts')
            ->join('items', 'stock_receipts.item_id', '=', 'items.id')
            ->join('categories', 'items.category_id', '=', 'categories.id')
            ->leftJoin('stock_issues', function ($join) {
                $join->on('stock_receipts.item_id', '=', 'stock_issues.item_id')
                    ->on('stock_receipts.store_id', '=', 'stock_issues.store_id');
            })
            ->when(request('category_id'), fn ($query, $value) => $query->where('categories.id', $value))
            ->select([
                'categories.name as category_name',
                DB::raw('SUM(stock_receipts.received_qty) as total_received'),
                DB::raw('COALESCE(SUM(stock_issues.issue_qty),0) as total_issued'),
                DB::raw('SUM(stock_receipts.received_qty) - COALESCE(SUM(stock_issues.issue_qty),0) as balance_qty'),
            ])
            ->groupBy('categories.name')
            ->orderBy('categories.name')
            ->get();

        return view('reports.index', compact(
            'filters',
            'categories',
            'items',
            'stores',
            'purchaseRequisitions',
            'purchaseOrders',
            'stockReceipts',
            'stockIssues',
            'storeBalances',
            'itemLedger',
            'personWise',
            'purposeWise',
            'categoryWise'
        ));
    }
}
