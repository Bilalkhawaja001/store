<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemRequest;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ItemController extends Controller
{
    public function index(): View
    {
        $items = Item::query()
            ->with('category')
            ->when(request('search'), function ($query, $search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('item_code', 'like', "%{$search}%")
                        ->orWhere('unit', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('items.index', compact('items'));
    }

    public function create(): View
    {
        $categories = Category::query()->orderBy('name')->get();

        return view('items.create', compact('categories'));
    }

    public function store(ItemRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['item_code'] = $data['item_code'] ?: 'ITM-'.now()->format('Ymd').'-'.str_pad((string) ((Item::max('id') ?? 0) + 1), 4, '0', STR_PAD_LEFT);
        $data['is_active'] = $request->boolean('is_active');

        Item::create($data);

        return redirect()->route('items.index')->with('success', 'Item created successfully.');
    }

    public function show(Item $item): View
    {
        $item->load('category');

        return view('items.show', compact('item'));
    }

    public function edit(Item $item): View
    {
        $categories = Category::query()->orderBy('name')->get();

        return view('items.edit', compact('item', 'categories'));
    }

    public function update(ItemRequest $request, Item $item): RedirectResponse
    {
        $data = $request->validated();
        $data['item_code'] = $data['item_code'] ?: $item->item_code;
        $data['is_active'] = $request->boolean('is_active');

        $item->update($data);

        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }

    public function destroy(Item $item): RedirectResponse
    {
        $item->delete();

        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }
}
