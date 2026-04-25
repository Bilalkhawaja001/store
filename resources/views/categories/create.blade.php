<x-app-layout>
    <x-slot name="header"><x-partials.page-header title="Add Category" description="Create a new category master." /></x-slot>
    <div class="card">
        <form method="POST" action="{{ route('categories.store') }}">
            @csrf
            @include('categories._form')
        </form>
    </div>
</x-app-layout>
