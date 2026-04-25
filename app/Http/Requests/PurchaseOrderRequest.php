<?php

namespace App\Http\Requests;

use App\Models\PurchaseOrder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PurchaseOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $purchaseOrderId = $this->route('purchase_order')?->id;

        return [
            'po_no' => ['nullable', 'string', 'max:255', Rule::unique('purchase_orders', 'po_no')->ignore($purchaseOrderId)],
            'po_date' => ['required', 'date'],
            'purchase_requisition_id' => ['required', 'exists:purchase_requisitions,id'],
            'vendor_name' => ['required', 'string', 'max:255'],
            'item_id' => ['required', 'exists:items,id'],
            'ordered_qty' => ['required', 'numeric', 'gt:0'],
            'unit_rate' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(PurchaseOrder::STATUSES)],
            'remarks' => ['nullable', 'string'],
        ];
    }
}
