<x-app-layout>
    <x-slot name="header"><x-partials.page-header title="Purchase Orders" description="Track orders raised against requisitions." action="{{ route('purchase-orders.create') }}" action-label="Add PO" /></x-slot>
    <div class="card">
        <x-partials.search-bar placeholder="Search PO no, vendor, or status..." />
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm"><thead><tr class="border-b border-slate-200 text-left text-slate-500"><th class="pb-3 pr-3">PO No</th><th class="pb-3 pr-3">Date</th><th class="pb-3 pr-3">PR No</th><th class="pb-3 pr-3">Vendor</th><th class="pb-3 pr-3">Item</th><th class="pb-3 pr-3">Ordered Qty</th><th class="pb-3 pr-3">Amount</th><th class="pb-3 pr-3">Status</th><th class="pb-3">Actions</th></tr></thead><tbody>
                @forelse ($purchaseOrders as $row)
                    <tr class="border-b border-slate-100"><td class="py-3 pr-3 font-semibold">{{ $row->po_no }}</td><td class="py-3 pr-3">{{ $row->po_date?->format('d M Y') }}</td><td class="py-3 pr-3">{{ $row->purchaseRequisition->pr_no }}</td><td class="py-3 pr-3">{{ $row->vendor_name }}</td><td class="py-3 pr-3">{{ $row->item->name }}</td><td class="py-3 pr-3">{{ number_format($row->ordered_qty, 2) }}</td><td class="py-3 pr-3">{{ number_format($row->total_amount, 2) }}</td><td class="py-3 pr-3"><span class="badge badge-slate">{{ $row->status }}</span></td><td class="py-3"><div class="flex flex-wrap gap-2"><a href="{{ route('purchase-orders.show', $row) }}" class="btn-secondary">View</a><a href="{{ route('purchase-orders.edit', $row) }}" class="btn-secondary">Edit</a><form method="POST" action="{{ route('purchase-orders.destroy', $row) }}">@csrf @method('DELETE')<button class="btn-secondary text-rose-600">Delete</button></form></div></td></tr>
                @empty
                    <tr><td colspan="9" class="py-8 text-center text-slate-500">No purchase orders found.</td></tr>
                @endforelse
            </tbody></table>
        </div>
        <div class="mt-6">{{ $purchaseOrders->links() }}</div>
    </div>
</x-app-layout>
