<x-app-layout>
    <x-slot name="header"><x-partials.page-header title="Stock Receiving" description="Receive stock against purchase orders." action="{{ route('stock-receipts.create') }}" action-label="Add Receipt" /></x-slot>

    @php($activeTab = request('tab', 'list'))

    <x-partials.page-tabs :tabs="[
        ['label' => 'Receipt List', 'href' => route('stock-receipts.index'), 'active' => $activeTab === 'list'],
        ['label' => 'Quick Receipt', 'href' => route('stock-receipts.index', ['tab' => 'create']), 'active' => $activeTab === 'create'],
        ['label' => 'Acquisition Summary', 'href' => route('stock-receipts.index', ['tab' => 'acquisition']), 'active' => $activeTab === 'acquisition'],
    ]" />

    @if ($activeTab === 'create')
        <div class="card">
            <form method="POST" action="{{ route('stock-receipts.store') }}">
                @csrf
                @include('stock-receipts._form')
            </form>
        </div>
    @elseif ($activeTab === 'acquisition')
        <div class="card overflow-x-auto">
            <h2 class="mb-4 text-lg font-semibold">Receipt Acquisition Summary</h2>
            <table class="min-w-full text-sm"><thead><tr class="border-b border-slate-200 text-left text-slate-500"><th class="pb-3 pr-3">Type</th><th class="pb-3 pr-3">Receipts</th><th class="pb-3 pr-3">Qty</th><th class="pb-3 pr-3">Loan-linked</th><th class="pb-3">Latest Receipt</th></tr></thead><tbody>
                @forelse ($acquisitionSummary as $row)
                    <tr class="border-b border-slate-100"><td class="py-3 pr-3">{{ $row->acquisition_type }}</td><td class="py-3 pr-3">{{ $row->receipt_count }}</td><td class="py-3 pr-3">{{ number_format($row->total_received_qty, 2) }}</td><td class="py-3 pr-3">{{ $row->loan_receipt_count }}</td><td class="py-3">{{ \Illuminate\Support\Carbon::parse($row->latest_receive_date)->format('d M Y') }}</td></tr>
                @empty
                    <tr><td colspan="5" class="py-8 text-center text-slate-500">No acquisition summary data found.</td></tr>
                @endforelse
            </tbody></table>
        </div>
    @else
        <div class="card">
            <x-partials.search-bar placeholder="Search GRN no, challan, receiver, handover, or acquisition details..." />
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm"><thead><tr class="border-b border-slate-200 text-left text-slate-500"><th class="pb-3 pr-3">GRN No</th><th class="pb-3 pr-3">Date</th><th class="pb-3 pr-3">PO No</th><th class="pb-3 pr-3">Item</th><th class="pb-3 pr-3">Type</th><th class="pb-3 pr-3">Reference</th><th class="pb-3 pr-3">Loan / Source</th><th class="pb-3 pr-3">Store</th><th class="pb-3 pr-3">Received</th><th class="pb-3 pr-3">Pending</th><th class="pb-3">Actions</th></tr></thead><tbody>
                    @forelse ($stockReceipts as $row)
                        <tr class="border-b border-slate-100"><td class="py-3 pr-3 font-semibold">{{ $row->grn_no }}</td><td class="py-3 pr-3">{{ $row->receive_date?->format('d M Y') }}</td><td class="py-3 pr-3">{{ $row->purchaseOrder->po_no }}</td><td class="py-3 pr-3">{{ $row->item->name }}</td><td class="py-3 pr-3">{{ $row->acquisition_type ?: '-' }}</td><td class="py-3 pr-3">{{ $row->acquisition_reference ?: '-' }}</td><td class="py-3 pr-3">{{ $row->lender_name ?: ($row->sourceStore?->name ?: '-') }}</td><td class="py-3 pr-3">{{ $row->store->name }}</td><td class="py-3 pr-3">{{ number_format($row->received_qty, 2) }}</td><td class="py-3 pr-3">{{ number_format($row->pending_qty, 2) }}</td><td class="py-3"><div class="flex flex-wrap gap-2"><a href="{{ route('stock-receipts.show', $row) }}" class="btn-secondary">View</a><form method="POST" action="{{ route('stock-receipts.destroy', $row) }}">@csrf @method('DELETE')<button class="btn-secondary text-rose-600">Delete</button></form></div></td></tr>
                    @empty
                        <tr><td colspan="11" class="py-8 text-center text-slate-500">No stock receipts found.</td></tr>
                    @endforelse
                </tbody></table>
            </div>
            <div class="mt-6">{{ $stockReceipts->links() }}</div>
        </div>
    @endif
</x-app-layout>
