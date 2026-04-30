<x-app-layout>
    <x-slot name="header"><x-partials.page-header title="Stores" description="Manage store masters." action="{{ route('stores.create') }}" action-label="Add Store" /></x-slot>

    <x-partials.page-tabs :tabs="[
        ['label' => 'Store List', 'href' => route('stores.index'), 'active' => $activeTab !== 'create'],
        ['label' => 'Quick Add', 'href' => route('stores.index', ['tab' => 'create']), 'active' => $activeTab === 'create'],
    ]" />

    @if ($activeTab === 'create')
        <div class="card">
            <form method="POST" action="{{ route('stores.store') }}">
                @csrf
                @include('stores._form')
            </form>
        </div>
    @else
        <div class="card">
            <x-partials.search-bar placeholder="Search store code, name, or location..." />
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm"><thead><tr class="border-b border-slate-200 text-left text-slate-500"><th class="pb-3 pr-3">Code</th><th class="pb-3 pr-3">Name</th><th class="pb-3 pr-3">Location</th><th class="pb-3 pr-3">Incharge</th><th class="pb-3 pr-3">Contact</th><th class="pb-3 pr-3">Status</th><th class="pb-3">Actions</th></tr></thead><tbody>
                    @forelse ($stores as $store)
                        <tr class="border-b border-slate-100"><td class="py-3 pr-3 font-semibold">{{ $store->store_code }}</td><td class="py-3 pr-3">{{ $store->name }}</td><td class="py-3 pr-3">{{ $store->location ?: '-' }}</td><td class="py-3 pr-3">{{ $store->incharge_name ?: '-' }}</td><td class="py-3 pr-3">{{ $store->contact_no ?: '-' }}</td><td class="py-3 pr-3"><span class="badge {{ $store->is_active ? 'badge-emerald' : 'badge-rose' }}">{{ $store->is_active ? 'Active' : 'Inactive' }}</span></td><td class="py-3"><div class="flex flex-wrap gap-2"><a href="{{ route('stores.show', $store) }}" class="btn-secondary">View</a><a href="{{ route('stores.edit', $store) }}" class="btn-secondary">Edit</a><form method="POST" action="{{ route('stores.destroy', $store) }}">@csrf @method('DELETE')<button class="btn-secondary text-rose-600">Delete</button></form></div></td></tr>
                    @empty
                        <tr><td colspan="7" class="py-8 text-center text-slate-500">No stores found.</td></tr>
                    @endforelse
                </tbody></table>
            </div>
            <div class="mt-6">{{ $stores->links() }}</div>
        </div>
    @endif
</x-app-layout>
