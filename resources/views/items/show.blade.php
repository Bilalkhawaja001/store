<x-app-layout>
    <x-slot name="header"><x-partials.page-header title="Item Details" description="View item master record." /></x-slot>
    <div class="card space-y-4">
        <div><span class="text-sm text-slate-500">Item Code</span><div class="text-lg font-semibold">{{ $item->item_code }}</div></div>
        <div><span class="text-sm text-slate-500">Name</span><div>{{ $item->name }}</div></div>
        <div><span class="text-sm text-slate-500">Category</span><div>{{ $item->category->name }}</div></div>
        <div><span class="text-sm text-slate-500">Unit</span><div>{{ $item->unit }}</div></div>
        <div><span class="text-sm text-slate-500">Minimum Stock</span><div>{{ number_format($item->minimum_stock_level, 2) }}</div></div>
        <div><span class="text-sm text-slate-500">Description</span><div>{{ $item->description ?: '-' }}</div></div>
        <div><span class="text-sm text-slate-500">Status</span><div>{{ $item->is_active ? 'Active' : 'Inactive' }}</div></div>
        <div class="flex gap-3"><a href="{{ route('items.edit', $item) }}" class="btn-primary">Edit</a><a href="{{ route('items.index') }}" class="btn-secondary">Back</a></div>
    </div>
</x-app-layout>
