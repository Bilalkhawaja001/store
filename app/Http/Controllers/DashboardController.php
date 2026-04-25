<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequisition;
use App\Models\StockIssue;
use App\Models\StockReceipt;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $totalReceived = (float) StockReceipt::sum('received_qty');
        $totalIssued = (float) StockIssue::sum('issue_qty');

        $lowStockItems = Item::query()
            ->leftJoin('stock_receipts', 'items.id', '=', 'stock_receipts.item_id')
            ->leftJoin('stock_issues', function ($join) {
                $join->on('items.id', '=', 'stock_issues.item_id')
                    ->on('stock_receipts.store_id', '=', 'stock_issues.store_id');
            })
            ->select('items.id', 'items.name', 'items.item_code', 'items.minimum_stock_level', DB::raw('COALESCE(SUM(DISTINCT stock_receipts.received_qty),0) - COALESCE(SUM(DISTINCT stock_issues.issue_qty),0) as balance_qty'))
            ->groupBy('items.id', 'items.name', 'items.item_code', 'items.minimum_stock_level')
            ->havingRaw('balance_qty <= minimum_stock_level')
            ->orderBy('balance_qty')
            ->limit(5)
            ->get();

        $stats = [
            'total_pr' => PurchaseRequisition::count(),
            'pending_pr' => PurchaseRequisition::where('status', 'Pending')->count(),
            'approved_pr' => PurchaseRequisition::where('status', 'Approved')->count(),
            'total_po' => PurchaseOrder::count(),
            'stock_received' => $totalReceived,
            'stock_issued' => $totalIssued,
            'available_balance' => $totalReceived - $totalIssued,
            'low_stock_items' => $lowStockItems->count(),
        ];

        $recentRequisitions = PurchaseRequisition::query()->with(['category', 'item'])->latest('pr_date')->limit(5)->get();
        $recentReceipts = StockReceipt::query()->with(['item', 'store'])->latest('receive_date')->limit(5)->get();

        return view('dashboard', compact('stats', 'lowStockItems', 'recentRequisitions', 'recentReceipts'));
    }
}
