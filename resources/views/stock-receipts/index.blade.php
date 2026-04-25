<x-app-layout>
    <x-slot name="header"><x-partials.page-header title="Stock Receiving" description="Receive stock against purchase orders." action="{{ route('stock-receipts.create') }}" action-label="Add Receipt" /></x-slot>
    <div class="card">
        <x-partials.search-bar placeholder="Search GRN no, challan, receiver, or handover..." />
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm"><thead><tr class="border-b border-slate-200 text-left text-slate-500"><th class="pb-3 pr-3">GRN No</th><th class="pb-3 pr-3">Date</th><th class="pb-3 pr-3">PO No</th><th class="pb-3 pr-3">Item</th><th class="pb-3 pr-3">Store</th><th class="pb-3 pr-3">Received</th><th class="pb-3 pr-3">Pending</th><th class="pb-3">Actions</th></tr></thead><tbody>
                @forelse ($stockReceipts as $row)
                    <tr class="border-b border-slate-100"><td class="py-3 pr-3 font-semibold">{{ $row->grn_no }}</td><td class="py-3 pr-3">{{ $row->receive_date?->format('d M Y') }}</td><td class="py-3 pr-3">{{ $row->purchaseOrder->po_no }}</td><td class="py-3 pr-3">{{ $row->item->name }}</td><td class="py-3 pr-3">{{ $row->store->name }}</td><td class="py-3 pr-3">{{ number_format($row->received_qty, 2) }}</td><td class="py-3 pr-3">{{ number_format($row->pending_qty, 2) }}</td><td class="py-3"><div class="flex flex-wrap gap-2"><a href="{{ route('stock-receipts.show', $row) }}" class="btn-secondary">View</a><form method="POST" action="{{ route('stock-receipts.destroy', $row) }}">@csrf @method('DELETE')<button class="btn-secondary text-rose-600">Delete</button></form></div></td></tr>
                @empty
                    <tr><td colspan="8" class="py-8 text-center text-slate-500">No stock receipts found.</td></tr>
                @endforelse
            </tbody></table>
        </div>
        <div class="mt-6">{{ $stockReceipts->links() }}</div>
    </div>
</x-app-layout>
