<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $storeId = $this->route('store')?->id;

        return [
            'store_code' => ['nullable', 'string', 'max:255', Rule::unique('stores', 'store_code')->ignore($storeId)],
            'name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'incharge_name' => ['nullable', 'string', 'max:255'],
            'contact_no' => ['nullable', 'string', 'max:50'],
            'remarks' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
