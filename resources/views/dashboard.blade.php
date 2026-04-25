<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Dashboard</h1>
            <p class="text-sm text-slate-500">Live overview of requisitions, purchase, receipts, issues, and balances.</p>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            @foreach ([
                'Total PR' => $stats['total_pr'],
                'Pending PR' => $stats['pending_pr'],
                'Approved PR' => $stats['approved_pr'],
                'Total PO' => $stats['total_po'],
                'Stock Received' => number_format($stats['stock_received'], 2),
                'Stock Issued' => number_format($stats['stock_issued'], 2),
                'Available Balance' => number_format($stats['available_balance'], 2),
                'Low Stock Items' => $stats['low_stock_items'],
            ] as $label => $value)
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">{{ $label }}</p>
                    <h3 class="mt-3 text-3xl font-bold text-slate-900">{{ $value }}</h3>
                </div>
            @endforeach
        </div>

        <div class="grid gap-6 xl:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm xl:col-span-2">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-slate-900">Recent Purchase Requisitions</h2>
                    <a href="{{ route('purchase-requisitions.index') }}" class="text-sm font-semibold text-sky-600">View all</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 text-left text-slate-500">
                                <th class="pb-3 pr-3">PR No</th>
                                <th class="pb-3 pr-3">Date</th>
                                <th class="pb-3 pr-3">Item</th>
                                <th class="pb-3 pr-3">Qty</th>
                                <th class="pb-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentRequisitions as $row)
                                <tr class="border-b border-slate-100">
                                    <td class="py-3 pr-3 font-semibold">{{ $row->pr_no }}</td>
                                    <td class="py-3 pr-3">{{ $row->pr_date?->format('d M Y') }}</td>
                                    <td class="py-3 pr-3">{{ $row->item->name }}</td>
                                    <td class="py-3 pr-3">{{ number_format($row->required_qty, 2) }}</td>
                                    <td class="py-3"><span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">{{ $row->status }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="py-6 text-center text-slate-500">No requisitions yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">Low Stock Items</h2>
                <div class="mt-4 space-y-3">
                    @forelse ($lowStockItems as $item)
                        <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3">
                            <div class="font-semibold text-slate-900">{{ $item->name }}</div>
                            <div class="text-sm text-slate-600">{{ $item->item_code }}</div>
                            <div class="mt-2 text-sm font-semibold text-amber-700">Balance: {{ number_format($item->balance_qty, 2) }}</div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No low stock item found.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-slate-900">Recent Stock Receipts</h2>
                <a href="{{ route('stock-receipts.index') }}" class="text-sm font-semibold text-sky-600">View all</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 text-left text-slate-500">
                            <th class="pb-3 pr-3">GRN No</th>
                            <th class="pb-3 pr-3">Date</th>
                            <th class="pb-3 pr-3">Item</th>
                            <th class="pb-3 pr-3">Store</th>
                            <th class="pb-3">Received Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentReceipts as $row)
                            <tr class="border-b border-slate-100">
                                <td class="py-3 pr-3 font-semibold">{{ $row->grn_no }}</td>
                                <td class="py-3 pr-3">{{ $row->receive_date?->format('d M Y') }}</td>
                                <td class="py-3 pr-3">{{ $row->item->name }}</td>
                                <td class="py-3 pr-3">{{ $row->store->name }}</td>
                                <td class="py-3">{{ number_format($row->received_qty, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-6 text-center text-slate-500">No receipts yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
