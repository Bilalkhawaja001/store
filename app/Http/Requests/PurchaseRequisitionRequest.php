<?php

namespace App\Http\Requests;

use App\Models\PurchaseRequisition;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PurchaseRequisitionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $purchaseRequisitionId = $this->route('purchase_requisition')?->id;

        return [
            'pr_no' => ['nullable', 'string', 'max:255', Rule::unique('purchase_requisitions', 'pr_no')->ignore($purchaseRequisitionId)],
            'pr_date' => ['required', 'date'],
            'category_id' => ['required', 'exists:categories,id'],
            'item_id' => ['required', 'exists:items,id'],
            'required_qty' => ['required', 'numeric', 'gt:0'],
            'purpose' => ['required', Rule::in(PurchaseRequisition::PURPOSES)],
            'required_for' => ['nullable', 'string', 'max:255'],
            'requested_by' => ['required', 'string', 'max:255'],
            'remarks' => ['nullable', 'string'],
            'status' => ['required', Rule::in(PurchaseRequisition::STATUSES)],
        ];
    }
}
