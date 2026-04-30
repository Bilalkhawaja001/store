@props(['tabs' => []])

@if (count($tabs))
    <div class="mb-6 flex flex-wrap gap-2 rounded-2xl border border-slate-200 bg-white p-2 shadow-sm">
        @foreach ($tabs as $tab)
            <a href="{{ $tab['href'] }}"
               class="rounded-xl px-4 py-2 text-sm font-medium transition {{ ($tab['active'] ?? false) ? 'bg-sky-500 text-white shadow-lg shadow-sky-500/20' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                {{ $tab['label'] }}
            </a>
        @endforeach
    </div>
@endif
