<x-app-layout>
    <x-slot name="header"><x-partials.page-header title="Stock Issue Details" description="View issue and usage transaction." /></x-slot>
    <div class="card space-y-4">
        <div><span class="text-sm text-slate-500">Issue No</span><div class="text-lg font-semibold">{{ $stockIssue->issue_no }}</div></div>
        <div><span class="text-sm text-slate-500">Store / Item</span><div>{{ $stockIssue->store->name }} / {{ $stockIssue->item->name }}</div></div>
        <div><span class="text-sm text-slate-500">Category</span><div>{{ $stockIssue->category->name }}</div></div>
        <div><span class="text-sm text-slate-500">Available Qty</span><div>{{ number_format($stockIssue->available_qty, 2) }}</div></div>
        <div><span class="text-sm text-slate-500">Issued Qty</span><div>{{ number_format($stockIssue->issue_qty, 2) }}</div></div>
        <div><span class="text-sm text-slate-500">Issued To</span><div>{{ $stockIssue->issued_to_person }}</div></div>
        <div><span class="text-sm text-slate-500">Usage Purpose</span><div>{{ $stockIssue->usage_purpose }}</div></div>
        <div><span class="text-sm text-slate-500">Department / Location</span><div>{{ $stockIssue->department_location ?: '-' }}</div></div>
        <div><span class="text-sm text-slate-500">Used At / Handover</span><div>{{ $stockIssue->used_at ?: '-' }} / {{ $stockIssue->handover_name ?: '-' }}</div></div>
        <div><span class="text-sm text-slate-500">PR / PO</span><div>{{ $stockIssue->purchaseRequisition?->pr_no ?: '-' }} / {{ $stockIssue->purchaseOrder?->po_no ?: '-' }}</div></div>
        <div><span class="text-sm text-slate-500">Remarks</span><div>{{ $stockIssue->remarks ?: '-' }}</div></div>
        <div class="flex gap-3"><a href="{{ route('stock-issues.index') }}" class="btn-secondary">Back</a></div>
    </div>
</x-app-layout>
