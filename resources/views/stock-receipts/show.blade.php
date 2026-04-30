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
        <div><span class="text-sm text-slate-500">Acquisition Type</span><div>{{ $stockReceipt->acquisition_type ?: '-' }}</div></div>
        <div><span class="text-sm text-slate-500">Acquisition Reference</span><div>{{ $stockReceipt->acquisition_reference ?: '-' }}</div></div>
        <div><span class="text-sm text-slate-500">Lender Name</span><div>{{ $stockReceipt->lender_name ?: '-' }}</div></div>
        <div><span class="text-sm text-slate-500">Loan Due Date</span><div>{{ $stockReceipt->loan_due_date?->format('d M Y') ?: '-' }}</div></div>
        <div><span class="text-sm text-slate-500">Loan Status</span><div>{{ $stockReceipt->loan_status ?: '-' }}</div></div>
        <div><span class="text-sm text-slate-500">Source Store</span><div>{{ $stockReceipt->sourceStore?->name ?: '-' }}</div></div>
        <div><span class="text-sm text-slate-500">Received By / Handover</span><div>{{ $stockReceipt->received_by }} / {{ $stockReceipt->handover_to ?: '-' }}</div></div>
        <div><span class="text-sm text-slate-500">Challan No</span><div>{{ $stockReceipt->challan_no ?: '-' }}</div></div>
        <div><span class="text-sm text-slate-500">Remarks</span><div>{{ $stockReceipt->remarks ?: '-' }}</div></div>
        <div class="flex gap-3"><a href="{{ route('stock-receipts.index') }}" class="btn-secondary">Back</a></div>
    </div>
</x-app-layout>
