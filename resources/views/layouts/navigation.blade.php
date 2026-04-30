<aside class="w-full border-b border-slate-200 bg-slate-900 text-slate-100 lg:min-h-screen lg:w-72 lg:border-b-0 lg:border-r lg:border-slate-800">
    <div class="flex h-full flex-col">
        <div class="border-b border-slate-800 px-6 py-6">
            <div class="flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-500 font-bold text-white shadow-lg shadow-sky-500/30">MT</div>
                <div>
                    <h2 class="text-lg font-bold text-white">Material Tracking</h2>
                    <p class="text-sm text-slate-400">Simple stock workflow</p>
                </div>
            </div>
        </div>

        <nav class="flex-1 space-y-1 px-4 py-6 text-sm font-medium">
            @php
                $links = [
                    ['label' => 'Dashboard', 'route' => 'dashboard'],
                    ['label' => 'Categories', 'route' => 'categories.index'],
                    ['label' => 'Items', 'route' => 'items.index'],
                    ['label' => 'Stores', 'route' => 'stores.index'],
                    ['label' => 'Purchase Requisitions', 'route' => 'purchase-requisitions.index'],
                    ['label' => 'Purchase Orders', 'route' => 'purchase-orders.index'],
                    ['label' => 'Stock Receiving', 'route' => 'stock-receipts.index'],
                    ['label' => 'Stock Issue / Usage', 'route' => 'stock-issues.index'],
                    ['label' => 'Reports', 'route' => 'reports.index'],
                ];
            @endphp

            @foreach ($links as $link)
                <a href="{{ route($link['route']) }}"
                   class="flex items-center rounded-xl px-4 py-3 transition {{ request()->routeIs(str_replace('.index', '.*', $link['route'])) || request()->routeIs($link['route']) ? 'bg-sky-500 text-white shadow-lg shadow-sky-500/20' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="border-t border-slate-800 px-4 py-4 text-sm text-slate-300">
            <div class="mb-3 rounded-xl bg-slate-800 px-4 py-3">
                <div class="font-semibold text-white">{{ auth()->user()->name ?? 'User' }}</div>
                <div class="text-slate-400">{{ auth()->user()->email ?? '' }}</div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('profile.edit') }}" class="flex-1 rounded-xl border border-slate-700 px-3 py-2 text-center hover:bg-slate-800">Profile</a>
                <form method="POST" action="{{ route('logout') }}" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full rounded-xl bg-rose-500 px-3 py-2 text-white hover:bg-rose-600">Logout</button>
                </form>
            </div>
        </div>
    </div>
</aside>
