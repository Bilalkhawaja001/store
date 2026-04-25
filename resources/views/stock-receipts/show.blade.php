<x-app-layout>
    <x-slot name="header"><x-partials.page-header title="Stock Receipt Details" description="View GRN and receipt transaction." /></x-slot>
    <div class="card space-y-4">
        <div><span class="text-sm text-slate-500">GRN No</span><div class="text-lg font-semibold">{{ $stockReceipt->grn_no }}</div></div>
        <div><span class="text-sm text-slate-500">PO / PR</span><div>{{ $stockReceipt->purchaseOrder->po_no }} / {{ $stockReceipt->purchaseRequisition->pr_no }}</div></div>
        <div><span class="text-sm text-slate-500">Item / Store</span><div>{{ $stockReceipt->item->name }} / {{ $stockReceipt->store->name }}</div></div>
        <div><span class="text-sm text-slate-500">Ordered Qty</span><div>{{ number_format($stockReceipt->ordered_qty, 2) }}</div></div>
        <div><span class="text-sm text-slate-500">Already Received</span><div>{{ number_format($stockReceipt->already_received_qty, 2) }}</div></div>
        <div><span class="text-sm text-slate-500">Received Qty</span><div>{{ number_format($stockReceipt->received_qty, 2) }}</div></div>
        <div><span class="text-sm text-slate-500">Pending Qty</span><div>{{ number_format($stockReceipt->pending_qty, 2) }}</div></div>
        <div><span class="text-sm text-slate-500">Received By / Handover</span><div>{{ $stockReceipt->received_by }} / {{ $stockReceipt->handover_to ?: '-' }}</div></div>
        <div><span class="text-sm text-slate-500">Challan No</span><div>{{ $stockReceipt->challan_no ?: '-' }}</div></div>
        <div><span class="text-sm text-slate-500">Remarks</span><div>{{ $stockReceipt->remarks ?: '-' }}</div></div>
        <div class="flex gap-3"><a href="{{ route('stock-receipts.index') }}" class="btn-secondary">Back</a></div>
    </div>
</x-app-layout>
