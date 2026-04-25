<?php

namespace App\Http\Requests;

use App\Models\PurchaseOrder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StockReceiptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'grn_no' => ['nullable', 'string', 'max:255'],
            'receive_date' => ['required', 'date'],
            'purchase_order_id' => ['required', 'exists:purchase_orders,id'],
            'purchase_requisition_id' => ['required', 'exists:purchase_requisitions,id'],
            'item_id' => ['required', 'exists:items,id'],
            'store_id' => ['required', 'exists:stores,id'],
            'received_qty' => ['required', 'numeric', 'gt:0'],
            'challan_no' => ['nullable', 'string', 'max:255'],
            'received_by' => ['required', 'string', 'max:255'],
            'handover_to' => ['nullable', 'string', 'max:255'],
            'remarks' => ['nullable', 'string'],
        ];
    }

    public function after(): array
    {
        return [function (Validator $validator): void {
            $purchaseOrder = PurchaseOrder::find($this->input('purchase_order_id'));

            if (! $purchaseOrder) {
                return;
            }

            if ((int) $purchaseOrder->purchase_requisition_id !== (int) $this->input('purchase_requisition_id')) {
                $validator->errors()->add('purchase_requisition_id', 'Selected PR does not belong to the selected PO.');
            }

            if ((int) $purchaseOrder->item_id !== (int) $this->input('item_id')) {
                $validator->errors()->add('item_id', 'Selected item does not belong to the selected PO.');
            }

            if ((float) $this->input('received_qty') > $purchaseOrder->pendingQty()) {
                $validator->errors()->add('received_qty', 'Receiving quantity cannot exceed pending PO quantity.');
            }
        }];
    }
}
