<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserGroupPriceRequest extends FormRequest
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
            'user_group' => 'required|integer',
            'shipping_id' => 'required|integer',
            'service_name' => 'required|string|max:100',
            'is_default' => 'in:1,0|required',
            'min' => 'required|min:0|max:299',
            'max' => 'required|min:0|max:300|gt:min',
            'price' => 'required|numeric|between:0,999999.99',
            'discount' => 'nullable|numeric|between:0,999999.99',
        ];

        return $rules;
    }
}
