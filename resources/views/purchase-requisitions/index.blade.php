<x-app-layout>
    <x-slot name="header"><x-partials.page-header title="Purchase Requisitions" description="Track material demand and approval flow." action="{{ route('purchase-requisitions.create') }}" action-label="Add PR" /></x-slot>
    <div class="card">
        <x-partials.search-bar placeholder="Search PR no, requestor, purpose, or status..." />
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm"><thead><tr class="border-b border-slate-200 text-left text-slate-500"><th class="pb-3 pr-3">PR No</th><th class="pb-3 pr-3">Date</th><th class="pb-3 pr-3">Category</th><th class="pb-3 pr-3">Item</th><th class="pb-3 pr-3">Qty</th><th class="pb-3 pr-3">Purpose</th><th class="pb-3 pr-3">Status</th><th class="pb-3">Actions</th></tr></thead><tbody>
                @forelse ($purchaseRequisitions as $row)
                    <tr class="border-b border-slate-100"><td class="py-3 pr-3 font-semibold">{{ $row->pr_no }}</td><td class="py-3 pr-3">{{ $row->pr_date?->format('d M Y') }}</td><td class="py-3 pr-3">{{ $row->category->name }}</td><td class="py-3 pr-3">{{ $row->item->name }}</td><td class="py-3 pr-3">{{ number_format($row->required_qty, 2) }}</td><td class="py-3 pr-3">{{ $row->purpose }}</td><td class="py-3 pr-3"><span class="badge badge-slate">{{ $row->status }}</span></td><td class="py-3"><div class="flex flex-wrap gap-2"><a href="{{ route('purchase-requisitions.show', $row) }}" class="btn-secondary">View</a><a href="{{ route('purchase-requisitions.edit', $row) }}" class="btn-secondary">Edit</a><form method="POST" action="{{ route('purchase-requisitions.destroy', $row) }}">@csrf @method('DELETE')<button class="btn-secondary text-rose-600">Delete</button></form></div></td></tr>
                @empty
                    <tr><td colspan="8" class="py-8 text-center text-slate-500">No purchase requisitions found.</td></tr>
                @endforelse
            </tbody></table>
        </div>
        <div class="mt-6">{{ $purchaseRequisitions->links() }}</div>
    </div>
</x-app-layout>
