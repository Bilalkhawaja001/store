<x-app-layout>
    <x-slot name="header"><x-partials.page-header title="Purchase Requisition Details" description="View PR information and linked purchase orders." /></x-slot>
    <div class="card space-y-4">
        <div><span class="text-sm text-slate-500">PR No</span><div class="text-lg font-semibold">{{ $purchaseRequisition->pr_no }}</div></div>
        <div><span class="text-sm text-slate-500">Date</span><div>{{ $purchaseRequisition->pr_date?->format('d M Y') }}</div></div>
        <div><span class="text-sm text-slate-500">Category / Item</span><div>{{ $purchaseRequisition->category->name }} / {{ $purchaseRequisition->item->name }}</div></div>
        <div><span class="text-sm text-slate-500">Required Qty</span><div>{{ number_format($purchaseRequisition->required_qty, 2) }}</div></div>
        <div><span class="text-sm text-slate-500">Purpose</span><div>{{ $purchaseRequisition->purpose }}</div></div>
        <div><span class="text-sm text-slate-500">Requested By</span><div>{{ $purchaseRequisition->requested_by }}</div></div>
        <div><span class="text-sm text-slate-500">Status</span><div>{{ $purchaseRequisition->status }}</div></div>
        <div><span class="text-sm text-slate-500">Remarks</span><div>{{ $purchaseRequisition->remarks ?: '-' }}</div></div>
        <div><span class="text-sm text-slate-500">Linked PO Count</span><div>{{ $purchaseRequisition->purchaseOrders->count() }}</div></div>
        <div class="flex gap-3"><a href="{{ route('purchase-requisitions.edit', $purchaseRequisition) }}" class="btn-primary">Edit</a><a href="{{ route('purchase-requisitions.index') }}" class="btn-secondary">Back</a></div>
    </div>
</x-app-layout>
