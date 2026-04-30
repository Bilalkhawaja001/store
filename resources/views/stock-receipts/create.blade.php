<x-app-layout>
    <x-slot name="header"><x-partials.page-header title="Add Stock Receipt" description="Receive stock against available PO balance." /></x-slot>

    <x-partials.page-tabs :tabs="[
        ['label' => 'Receipt List', 'href' => route('stock-receipts.index'), 'active' => false],
        ['label' => 'Add Receipt', 'href' => route('stock-receipts.create'), 'active' => true],
        ['label' => 'Reports', 'href' => route('reports.index', ['tab' => 'acquisition-summary']), 'active' => false],
    ]" />

    <div class="card">
        <form method="POST" action="{{ route('stock-receipts.store') }}">
            @csrf
            @include('stock-receipts._form')
        </form>
    </div>
</x-app-layout>
