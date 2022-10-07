<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CurrencyRequest extends FormRequest
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
            'active' => 'required|in:1,0',
            'value' => 'required|numeric',
            'symbol' => 'nullable',
            'country' => 'required|max:30',
            'code' => 'required|max:3',
        ];

        return $rules;
    }
}
