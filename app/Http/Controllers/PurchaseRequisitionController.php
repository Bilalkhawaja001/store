<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequisitionRequest;
use App\Models\Category;
use App\Models\Item;
use App\Models\PurchaseRequisition;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PurchaseRequisitionController extends Controller
{
    public function index(): View
    {
        $purchaseRequisitions = PurchaseRequisition::query()
            ->with(['category', 'item'])
            ->when(request('search'), function ($query, $search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('pr_no', 'like', "%{$search}%")
                        ->orWhere('requested_by', 'like', "%{$search}%")
                        ->orWhere('purpose', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%");
                });
            })
            ->latest('pr_date')
            ->paginate(10)
            ->withQueryString();

        return view('purchase-requisitions.index', compact('purchaseRequisitions'));
    }

    public function create(): View
    {
        return view('purchase-requisitions.create', [
            'categories' => Category::query()->orderBy('name')->get(),
            'items' => Item::query()->with('category')->orderBy('name')->get(),
            'purposes' => PurchaseRequisition::PURPOSES,
            'statuses' => PurchaseRequisition::STATUSES,
        ]);
    }

    public function store(PurchaseRequisitionRequest $request): RedirectResponse
    {
        PurchaseRequisition::create($request->validated());

        return redirect()->route('purchase-requisitions.index')->with('success', 'Purchase requisition created successfully.');
    }

    public function show(PurchaseRequisition $purchaseRequisition): View
    {
        $purchaseRequisition->load(['category', 'item', 'purchaseOrders']);

        return view('purchase-requisitions.show', compact('purchaseRequisition'));
    }

    public function edit(PurchaseRequisition $purchaseRequisition): View
    {
        return view('purchase-requisitions.edit', [
            'purchaseRequisition' => $purchaseRequisition,
            'categories' => Category::query()->orderBy('name')->get(),
            'items' => Item::query()->with('category')->orderBy('name')->get(),
            'purposes' => PurchaseRequisition::PURPOSES,
            'statuses' => PurchaseRequisition::STATUSES,
        ]);
    }

    public function update(PurchaseRequisitionRequest $request, PurchaseRequisition $purchaseRequisition): RedirectResponse
    {
        $purchaseRequisition->update($request->validated());

        return redirect()->route('purchase-requisitions.index')->with('success', 'Purchase requisition updated successfully.');
    }

    public function destroy(PurchaseRequisition $purchaseRequisition): RedirectResponse
    {
        $purchaseRequisition->delete();

        return redirect()->route('purchase-requisitions.index')->with('success', 'Purchase requisition deleted successfully.');
    }
}
