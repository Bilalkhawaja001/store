<x-app-layout>
    <x-slot name="header"><x-partials.page-header title="Purchase Order Details" description="View PO and receipt summary." /></x-slot>
    <div class="card space-y-4">
        <div><span class="text-sm text-slate-500">PO No</span><div class="text-lg font-semibold">{{ $purchaseOrder->po_no }}</div></div>
        <div><span class="text-sm text-slate-500">PR No</span><div>{{ $purchaseOrder->purchaseRequisition->pr_no }}</div></div>
        <div><span class="text-sm text-slate-500">Item / Vendor</span><div>{{ $purchaseOrder->item->name }} / {{ $purchaseOrder->vendor_name }}</div></div>
        <div><span class="text-sm text-slate-500">Ordered Qty</span><div>{{ number_format($purchaseOrder->ordered_qty, 2) }}</div></div>
        <div><span class="text-sm text-slate-500">Total Amount</span><div>{{ number_format($purchaseOrder->total_amount, 2) }}</div></div>
        <div><span class="text-sm text-slate-500">Status</span><div>{{ $purchaseOrder->status }}</div></div>
        <div><span class="text-sm text-slate-500">Received Qty</span><div>{{ number_format($purchaseOrder->totalReceivedQty(), 2) }}</div></div>
        <div><span class="text-sm text-slate-500">Pending Qty</span><div>{{ number_format($purchaseOrder->pendingQty(), 2) }}</div></div>
        <div><span class="text-sm text-slate-500">Remarks</span><div>{{ $purchaseOrder->remarks ?: '-' }}</div></div>
        <div class="flex gap-3"><a href="{{ route('purchase-orders.edit', $purchaseOrder) }}" class="btn-primary">Edit</a><a href="{{ route('purchase-orders.index') }}" class="btn-secondary">Back</a></div>
    </div>
</x-app-layout>
