<x-app-layout>
    <x-slot name="header"><x-partials.page-header title="Items" description="Manage material items." action="{{ route('items.create') }}" action-label="Add Item" /></x-slot>

    <x-partials.page-tabs :tabs="[
        ['label' => 'Item List', 'href' => route('items.index'), 'active' => $activeTab !== 'create'],
        ['label' => 'Quick Add', 'href' => route('items.index', ['tab' => 'create']), 'active' => $activeTab === 'create'],
    ]" />

    @if ($activeTab === 'create')
        <div class="card">
            <form method="POST" action="{{ route('items.store') }}">
                @csrf
                @include('items._form')
            </form>
        </div>
    @else
        <div class="card">
            <x-partials.search-bar placeholder="Search item code, name, or unit..." />
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead><tr class="border-b border-slate-200 text-left text-slate-500"><th class="pb-3 pr-3">Code</th><th class="pb-3 pr-3">Name</th><th class="pb-3 pr-3">Category</th><th class="pb-3 pr-3">Unit</th><th class="pb-3 pr-3">Min Stock</th><th class="pb-3 pr-3">Status</th><th class="pb-3">Actions</th></tr></thead>
                    <tbody>
                        @forelse ($items as $item)
                            <tr class="border-b border-slate-100">
                                <td class="py-3 pr-3 font-semibold">{{ $item->item_code }}</td>
                                <td class="py-3 pr-3">{{ $item->name }}</td>
                                <td class="py-3 pr-3">{{ $item->category->name }}</td>
                                <td class="py-3 pr-3">{{ $item->unit }}</td>
                                <td class="py-3 pr-3">{{ number_format($item->minimum_stock_level, 2) }}</td>
                                <td class="py-3 pr-3"><span class="badge {{ $item->is_active ? 'badge-emerald' : 'badge-rose' }}">{{ $item->is_active ? 'Active' : 'Inactive' }}</span></td>
                                <td class="py-3"><div class="flex flex-wrap gap-2"><a href="{{ route('items.show', $item) }}" class="btn-secondary">View</a><a href="{{ route('items.edit', $item) }}" class="btn-secondary">Edit</a><form method="POST" action="{{ route('items.destroy', $item) }}">@csrf @method('DELETE')<button class="btn-secondary text-rose-600">Delete</button></form></div></td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="py-8 text-center text-slate-500">No items found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-6">{{ $items->links() }}</div>
        </div>
    @endif
</x-app-layout>
