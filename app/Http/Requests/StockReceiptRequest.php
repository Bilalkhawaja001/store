<?php

namespace App\Http\Requests;

use App\Models\PurchaseOrder;
use App\Models\StockReceipt;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
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
            'acquisition_type' => ['nullable', 'string', Rule::in(StockReceipt::ACQUISITION_TYPES)],
            'acquisition_reference' => ['nullable', 'string', 'max:255'],
            'lender_name' => ['nullable', 'string', 'max:255'],
            'loan_due_date' => ['nullable', 'date'],
            'loan_status' => ['nullable', 'string', Rule::in(StockReceipt::LOAN_STATUSES)],
            'source_store_id' => ['nullable', 'different:store_id', 'exists:stores,id'],
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

            if ($this->input('acquisition_type') === 'Loan') {
                if (blank($this->input('lender_name'))) {
                    $validator->errors()->add('lender_name', 'Lender name is required for loan receipts.');
                }

                if (blank($this->input('loan_due_date'))) {
                    $validator->errors()->add('loan_due_date', 'Loan due date is required for loan receipts.');
                }

                if (blank($this->input('loan_status'))) {
                    $validator->errors()->add('loan_status', 'Loan status is required for loan receipts.');
                }
            }
        }];
    }
}
