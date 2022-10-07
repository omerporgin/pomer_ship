<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShippingRequest extends FormRequest
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
            'is_active' => 'nullable|integer',
            'is_test' => 'nullable|integer',

            'account_number' => 'nullable|string|max:100',
            'user' => 'nullable|string|max:100',
            'api_key' => 'nullable|string|max:100',
            'api_secret' => 'nullable|string|max:100',

            'test_account_number' => 'nullable|string|max:100',
            'test_user' => 'nullable|string|max:100',
            'test_api_key' => 'nullable|string|max:100',
            'test_api_secret' => 'nullable|string|max:100',

            'zone_field' => 'nullable|string|max:100',
            'sort' => 'nullable|integer',
        ];

        return $rules;
    }
}
