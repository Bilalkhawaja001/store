<?php

namespace App\Http\Requests;

use App\Models\StockReceipt;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StockIssueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'issue_no' => ['nullable', 'string', 'max:255'],
            'issue_date' => ['required', 'date'],
            'store_id' => ['required', 'exists:stores,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'item_id' => ['required', 'exists:items,id'],
            'purchase_requisition_id' => ['nullable', 'exists:purchase_requisitions,id'],
            'purchase_order_id' => ['nullable', 'exists:purchase_orders,id'],
            'available_qty' => ['nullable', 'numeric', 'min:0'],
            'issue_qty' => ['required', 'numeric', 'gt:0'],
            'issued_to_person' => ['required', 'string', 'max:255'],
            'department_location' => ['nullable', 'string', 'max:255'],
            'used_at' => ['nullable', 'string', 'max:255'],
            'usage_purpose' => ['required', 'string', 'max:255'],
            'handover_name' => ['nullable', 'string', 'max:255'],
            'remarks' => ['nullable', 'string'],
        ];
    }

    public function after(): array
    {
        return [function (Validator $validator): void {
            $availableQty = StockReceipt::query()
                ->where('store_id', $this->input('store_id'))
                ->where('item_id', $this->input('item_id'))
                ->sum('received_qty')
                - \App\Models\StockIssue::query()
                    ->where('store_id', $this->input('store_id'))
                    ->where('item_id', $this->input('item_id'))
                    ->sum('issue_qty');

            if ((float) $this->input('issue_qty') > (float) $availableQty) {
                $validator->errors()->add('issue_qty', 'Issue quantity cannot exceed available stock.');
            }
        }];
    }
}
