<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EntegrationDataRequest extends FormRequest
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
            'entegration_id' => 'integer',
            'url' => 'required',
            'user' => 'required',
            'pass' => 'required',
            'user_id' => 'nullable',
            'last_date' => 'required|date',
            'days' => 'required|integer|min:1',
            'cargo_id' => 'nullable|regex:/^[,0-9]+$/',
            'statuses' => 'nullable|string',
            'max' => 'required|integer|max:100|min:1',
        ];

        return $rules;
    }
}
