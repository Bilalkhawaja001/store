<x-app-layout>
    <x-slot name="header"><x-partials.page-header title="Category Details" description="View category information." /></x-slot>
    <div class="card space-y-4">
        <div><span class="text-sm text-slate-500">Name</span><div class="text-lg font-semibold">{{ $category->name }}</div></div>
        <div><span class="text-sm text-slate-500">Description</span><div>{{ $category->description ?: '-' }}</div></div>
        <div><span class="text-sm text-slate-500">Status</span><div>{{ $category->is_active ? 'Active' : 'Inactive' }}</div></div>
        <div class="flex gap-3"><a href="{{ route('categories.edit', $category) }}" class="btn-primary">Edit</a><a href="{{ route('categories.index') }}" class="btn-secondary">Back</a></div>
    </div>
</x-app-layout>
