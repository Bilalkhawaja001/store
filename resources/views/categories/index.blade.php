<x-app-layout>
    <x-slot name="header"><x-partials.page-header title="Categories" description="Manage item categories." action="{{ route('categories.create') }}" action-label="Add Category" /></x-slot>

    <x-partials.page-tabs :tabs="[
        ['label' => 'Category List', 'href' => route('categories.index'), 'active' => $activeTab !== 'create'],
        ['label' => 'Quick Add', 'href' => route('categories.index', ['tab' => 'create']), 'active' => $activeTab === 'create'],
    ]" />

    @if ($activeTab === 'create')
        <div class="card">
            <form method="POST" action="{{ route('categories.store') }}">
                @csrf
                @include('categories._form')
            </form>
        </div>
    @else
        <div class="card">
            <x-partials.search-bar placeholder="Search category name..." />
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 text-left text-slate-500">
                            <th class="pb-3 pr-3">Name</th><th class="pb-3 pr-3">Description</th><th class="pb-3 pr-3">Status</th><th class="pb-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                            <tr class="border-b border-slate-100">
                                <td class="py-3 pr-3 font-semibold">{{ $category->name }}</td>
                                <td class="py-3 pr-3">{{ $category->description ?: '-' }}</td>
                                <td class="py-3 pr-3"><span class="badge {{ $category->is_active ? 'badge-emerald' : 'badge-rose' }}">{{ $category->is_active ? 'Active' : 'Inactive' }}</span></td>
                                <td class="py-3">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('categories.show', $category) }}" class="btn-secondary">View</a>
                                        <a href="{{ route('categories.edit', $category) }}" class="btn-secondary">Edit</a>
                                        <form action="{{ route('categories.destroy', $category) }}" method="POST">@csrf @method('DELETE')<button class="btn-secondary text-rose-600">Delete</button></form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-8 text-center text-slate-500">No categories found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-6">{{ $categories->links() }}</div>
        </div>
    @endif
</x-app-layout>
