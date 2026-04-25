<x-app-layout>
    <x-slot name="header"><x-partials.page-header title="Store Details" description="View store information." /></x-slot>
    <div class="card space-y-4">
        <div><span class="text-sm text-slate-500">Store Code</span><div class="text-lg font-semibold">{{ $store->store_code }}</div></div>
        <div><span class="text-sm text-slate-500">Name</span><div>{{ $store->name }}</div></div>
        <div><span class="text-sm text-slate-500">Location</span><div>{{ $store->location ?: '-' }}</div></div>
        <div><span class="text-sm text-slate-500">Incharge</span><div>{{ $store->incharge_name ?: '-' }}</div></div>
        <div><span class="text-sm text-slate-500">Contact</span><div>{{ $store->contact_no ?: '-' }}</div></div>
        <div><span class="text-sm text-slate-500">Remarks</span><div>{{ $store->remarks ?: '-' }}</div></div>
        <div><span class="text-sm text-slate-500">Status</span><div>{{ $store->is_active ? 'Active' : 'Inactive' }}</div></div>
        <div class="flex gap-3"><a href="{{ route('stores.edit', $store) }}" class="btn-primary">Edit</a><a href="{{ route('stores.index') }}" class="btn-secondary">Back</a></div>
    </div>
</x-app-layout>
