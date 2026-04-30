<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequest;
use App\Models\Store;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class StoreController extends Controller
{
    public function index(): View
    {
        $stores = Store::query()
            ->when(request('search'), function ($query, $search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('store_code', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('stores.index', [
            'stores' => $stores,
            'activeTab' => request('tab', 'list'),
        ]);
    }

    public function create(): View
    {
        return view('stores.create');
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['store_code'] = $data['store_code'] ?: 'STR-'.now()->format('Ymd').'-'.str_pad((string) ((Store::max('id') ?? 0) + 1), 4, '0', STR_PAD_LEFT);
        $data['is_active'] = $request->boolean('is_active');

        Store::create($data);

        return redirect()->route('stores.index')->with('success', 'Store created successfully.');
    }

    public function show(Store $store): View
    {
        return view('stores.show', compact('store'));
    }

    public function edit(Store $store): View
    {
        return view('stores.edit', compact('store'));
    }

    public function update(StoreRequest $request, Store $store): RedirectResponse
    {
        $data = $request->validated();
        $data['store_code'] = $data['store_code'] ?: $store->store_code;
        $data['is_active'] = $request->boolean('is_active');

        $store->update($data);

        return redirect()->route('stores.index')->with('success', 'Store updated successfully.');
    }

    public function destroy(Store $store): RedirectResponse
    {
        $store->delete();

        return redirect()->route('stores.index')->with('success', 'Store deleted successfully.');
    }
}
