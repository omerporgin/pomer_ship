<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocationCityRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'state_id' => 'required|integer',
            'state_code' => 'nullable|string|max:100',
            'country_id' => 'required|integer',
            'country_code' => 'nullable|string|max:100',
            'latitude' => ['required', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'longitude' => ['required', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
            'flag' => 'required|in:1,0',
            'wikiDataId' => 'required|max:255',
            'is_accepted' => 'nullable|in:1,0',
        ];

        return $rules;
    }
}
