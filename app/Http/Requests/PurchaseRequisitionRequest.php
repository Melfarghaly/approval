<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class PurchaseRequisitionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'location_id' => ['required', 'int', 'exists:business_locations,id'],
            'ref_no' => ['nullable', 'numeric', 'unique:transactions,ref_no'],
            'delivery_date' => ['nullable', 'date'],
            'purchases' => ['required', 'array'],
            'purchases.*.quantity' => ['required', 'int', 'min:1'],
            'purchases.*.variation_id' => ['required', 'int'],
            'purchases.*.product_id' => ['required', 'int'],
        ];
    }
}
