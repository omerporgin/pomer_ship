<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\HiddenChars;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'id' => 'nullable|integer',
            'status' => 'nullable|integer|max:255', // unsigned tinyint max
            'real_status' => 'nullable|integer|max:255', // unsigned tinyint max
            'vendor_id' => 'nullable|integer', // Required for admin, Nullable for user
            'entegration_id' => 'nullable|integer',
            'order_id' => 'nullable|string', // Siteden açılan orderlarda order_id alanı yok.

            // Price
            'currency' => 'required|integer',
            'total_price' => 'nullable|numeric|between:0,99999999.99',
            'declared_price' => 'nullable|numeric|between:0,99999999.99',
            'se_label' => 'nullable|string',
            'invoice_no' => 'required|string',

            // Personal data
            'full_name' =>  [new HiddenChars(), 'required', 'string', 'max:255'],
            'company_name' => [new HiddenChars(), 'nullable', 'string', 'max:100'],
            'phone' =>  [new HiddenChars(), 'required', 'string', 'max:100'],
            'email' => [new HiddenChars(), 'required', 'string', 'max:100'],

            // Shipment details
            'message' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:500',
            'address' =>  [new HiddenChars(), 'required', 'string', 'max:1000'],
            'country_id' => 'required|integer',
            'state_id' => 'required|integer',
            'city_id' => 'nullable|integer',
            'post_code' => 'nullable|string|max:10',

            // pickup
            'has_pickup' => 'nullable|in:1,0',
            'has_diffrent_pickup_address' => 'nullable|in:1,0',
            'pickup_closed_at' => 'date_format:H:i|nullable',
            'pickup_location' => 'nullable|string|max:80',
            'pickup_address' => 'nullable|string|max:1000',
            'pickup_state_id' => 'nullable|integer',
            'pickup_city_id' => 'nullable|integer',
            'pickup_post_code' => 'nullable|string|max:10',

            // Package info
            'shipment_id' => 'nullable|integer',
            'tracking_number' => 'nullable|string|max:50',
            'tracking_status' => 'nullable|integer',

            'product_id' => 'array',
            'product_id.*' => 'nullable|integer',
            'name' => 'array',
            'name.*' => 'nullable|string',
            'type' => 'array',
            'type.*' => 'nullable|integer',
            'name_en' => 'array',
            'name_en.*' => 'nullable|string',
            'unit_price' => 'array',
            'unit_price.*' => 'nullable|numeric|between:0,999999.99',
            'sku' => 'array',
            'sku.*' => 'nullable|string',
            'quantity' => 'array',
            'quantity.*' => 'required|integer',
            'gtip_code' => 'array',
            'gtip_code.*' => 'nullable|string',

            'product_width' => 'array',
            'product_width.*' => 'nullable|numeric|between:0,999999.99',
            'product_height' => 'array',
            'product_height.*' => 'nullable|numeric|between:0,999999.99',
            'product_length' => 'array',
            'product_length.*' => 'nullable|numeric|between:0,999999.99',
            'product_weight' => 'array',
            'product_weight.*' => 'nullable|numeric|between:0,999999.99',

            'package' => 'nullable', // stores new products package data

            'package_list' => 'required',
            'shipment_id' => 'nullable|integer',
            'order_date' => ['required', 'date', 'date_format:Y-m-d'],
            'data' => 'string|nullable',

            // Package data
            'package_width' => 'array',
            'package_width.*' => 'nullable|numeric|between:0,999999.99',
            'package_height' => 'array',
            'package_height.*' => 'nullable|numeric|between:0,999999.99',
            'package_length' => 'array',
            'package_length.*' => 'nullable|numeric|between:0,999999.99',
            'package_weight' => 'array',
            'package_weight.*' => 'nullable|numeric|between:0,999999.99',
            'package_id' => 'array',
            'package_id.*' => 'nullable|integer',

            'shipped_at' => ['required', 'date_format:Y-m-d', 'after:' . date('Y-m-d')],
        ];

        return $rules;
    }
}
