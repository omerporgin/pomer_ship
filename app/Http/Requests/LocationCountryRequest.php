<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocationCountryRequest extends FormRequest
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
            'name' => 'required|max:100',
            'iso3' => 'required|max:3',
            'numeric_code' => 'required|max:3',
            'iso2' => 'required|max:3',
            'phonecode' => 'required|max:255',
            'capital' => 'required|max:255',
            'currency' => 'required|max:255',
            'currency_name' => 'required|max:255',
            'currency_symbol' => 'required|max:255',
            'tld' => 'required|max:255',
            'native' => 'required|max:255',
            'region' => 'required|max:255',
            'subregion' => 'required|max:255',
            'timezones' => 'nullable|string',
            'translations' => 'nullable|string',
            'latitude' => ['required', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'longitude' => ['required', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
            'emoji' => 'required|max:191',
            'emojiU' => 'required|max:191',
            'flag' => 'required|in:1,0',
            'wikiDataId' => 'required|max:255',
            'is_accepted' => 'required|in:1,0',
        ];

        return $rules;
    }
}
