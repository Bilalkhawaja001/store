<x-app-layout>
    <x-slot name="header"><x-partials.page-header title="Stock Issue / Usage" description="Issue stock from store to end users or work locations." action="{{ route('stock-issues.create') }}" action-label="Add Issue" /></x-slot>
    <div class="card">
        <x-partials.search-bar placeholder="Search issue no, person, purpose, or handover..." />
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm"><thead><tr class="border-b border-slate-200 text-left text-slate-500"><th class="pb-3 pr-3">Issue No</th><th class="pb-3 pr-3">Date</th><th class="pb-3 pr-3">Store</th><th class="pb-3 pr-3">Item</th><th class="pb-3 pr-3">Available</th><th class="pb-3 pr-3">Issued</th><th class="pb-3 pr-3">Issued To</th><th class="pb-3 pr-3">Purpose</th><th class="pb-3">Actions</th></tr></thead><tbody>
                @forelse ($stockIssues as $row)
                    <tr class="border-b border-slate-100"><td class="py-3 pr-3 font-semibold">{{ $row->issue_no }}</td><td class="py-3 pr-3">{{ $row->issue_date?->format('d M Y') }}</td><td class="py-3 pr-3">{{ $row->store->name }}</td><td class="py-3 pr-3">{{ $row->item->name }}</td><td class="py-3 pr-3">{{ number_format($row->available_qty, 2) }}</td><td class="py-3 pr-3">{{ number_format($row->issue_qty, 2) }}</td><td class="py-3 pr-3">{{ $row->issued_to_person }}</td><td class="py-3 pr-3">{{ $row->usage_purpose }}</td><td class="py-3"><div class="flex flex-wrap gap-2"><a href="{{ route('stock-issues.show', $row) }}" class="btn-secondary">View</a><form method="POST" action="{{ route('stock-issues.destroy', $row) }}">@csrf @method('DELETE')<button class="btn-secondary text-rose-600">Delete</button></form></div></td></tr>
                @empty
                    <tr><td colspan="9" class="py-8 text-center text-slate-500">No stock issues found.</td></tr>
                @endforelse
            </tbody></table>
        </div>
        <div class="mt-6">{{ $stockIssues->links() }}</div>
    </div>
</x-app-layout>
