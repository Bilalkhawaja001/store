<x-app-layout>
    <x-slot name="header"><x-partials.page-header title="Add Stock Receipt" description="Receive stock against available PO balance." /></x-slot>
    <div class="card">
        <form method="POST" action="{{ route('stock-receipts.store') }}">
            @csrf
            <div class="grid gap-6 md:grid-cols-2">
                <div><x-input-label for="grn_no" value="GRN No" /><input id="grn_no" name="grn_no" type="text" class="input" value="{{ old('grn_no') }}"><x-input-error :messages="$errors->get('grn_no')" class="mt-2" /></div>
                <div><x-input-label for="receive_date" value="Receive Date" /><input id="receive_date" name="receive_date" type="date" class="input" value="{{ old('receive_date', now()->format('Y-m-d')) }}" required><x-input-error :messages="$errors->get('receive_date')" class="mt-2" /></div>
                <div><x-input-label for="purchase_order_id" value="Purchase Order" /><select id="purchase_order_id" name="purchase_order_id" class="select" required><option value="">Select</option>@foreach($purchaseOrders as $po)<option value="{{ $po->id }}" @selected(old('purchase_order_id') == $po->id)>{{ $po->po_no }} | {{ $po->item->name }} | Pending {{ number_format($po->pendingQty(), 2) }}</option>@endforeach</select><x-input-error :messages="$errors->get('purchase_order_id')" class="mt-2" /></div>
                <div><x-input-label for="purchase_requisition_id" value="Purchase Requisition" /><select id="purchase_requisition_id" name="purchase_requisition_id" class="select" required><option value="">Select</option>@foreach($purchaseRequisitions as $pr)<option value="{{ $pr->id }}" @selected(old('purchase_requisition_id') == $pr->id)>{{ $pr->pr_no }}</option>@endforeach</select><x-input-error :messages="$errors->get('purchase_requisition_id')" class="mt-2" /></div>
                <div><x-input-label for="item_id" value="Item" /><select id="item_id" name="item_id" class="select" required><option value="">Select</option>@foreach($purchaseOrders as $po)<option value="{{ $po->item->id }}" @selected(old('item_id') == $po->item->id)>{{ $po->item->name }} ({{ $po->item->item_code }})</option>@endforeach</select><x-input-error :messages="$errors->get('item_id')" class="mt-2" /></div>
                <div><x-input-label for="store_id" value="Store" /><select id="store_id" name="store_id" class="select" required><option value="">Select</option>@foreach($stores as $store)<option value="{{ $store->id }}" @selected(old('store_id') == $store->id)>{{ $store->name }}</option>@endforeach</select><x-input-error :messages="$errors->get('store_id')" class="mt-2" /></div>
                <div><x-input-label for="received_qty" value="Received Qty" /><input id="received_qty" name="received_qty" type="number" step="0.01" class="input" value="{{ old('received_qty') }}" required><x-input-error :messages="$errors->get('received_qty')" class="mt-2" /></div>
                <div><x-input-label for="challan_no" value="Challan No" /><input id="challan_no" name="challan_no" type="text" class="input" value="{{ old('challan_no') }}"><x-input-error :messages="$errors->get('challan_no')" class="mt-2" /></div>
                <div><x-input-label for="received_by" value="Received By" /><input id="received_by" name="received_by" type="text" class="input" value="{{ old('received_by', auth()->user()->name) }}" required><x-input-error :messages="$errors->get('received_by')" class="mt-2" /></div>
                <div><x-input-label for="handover_to" value="Handover To" /><input id="handover_to" name="handover_to" type="text" class="input" value="{{ old('handover_to') }}"><x-input-error :messages="$errors->get('handover_to')" class="mt-2" /></div>
                <div class="md:col-span-2"><x-input-label for="remarks" value="Remarks" /><textarea id="remarks" name="remarks" class="textarea" rows="4">{{ old('remarks') }}</textarea><x-input-error :messages="$errors->get('remarks')" class="mt-2" /></div>
            </div>
            <div class="mt-6 flex gap-3"><button class="btn-primary">Save</button><a href="{{ route('stock-receipts.index') }}" class="btn-secondary">Cancel</a></div>
        </form>
    </div>
</x-app-layout>
