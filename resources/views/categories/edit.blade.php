<x-app-layout>
    <x-slot name="header"><x-partials.page-header title="Edit Category" description="Update category details." /></x-slot>
    <div class="card">
        <form method="POST" action="{{ route('categories.update', $category) }}">
            @csrf
            @method('PUT')
            @include('categories._form')
        </form>
    </div>
</x-app-layout>
