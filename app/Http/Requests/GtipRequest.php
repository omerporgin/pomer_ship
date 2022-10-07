<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GtipRequest extends FormRequest
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
            'gtip' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1500',
            'is_selectable' => 'required|in:0,1',
            'search' => 'nullable|string',
            'unit' => 'nullable|string|max:100',
            'tax' => 'nullable|string|max:100',
        ];

        return $rules;
    }
}
