<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
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
            'lang' => 'required|integer',
            'user_id' => 'nullable|integer',
            'headline' => 'required|string|max:150',
            'url' => 'nullable|string|max:100',
            'lead' => 'nullable|string|max:255',
            'body' => 'nullable|string',
            'title' => 'nullable|string|max:150',
            'description' => 'required|string|max:255',

            'category_default' => 'nullable|array',
            'category_default.*' => 'required|integer',
            'category_list' => 'nullable|array',
            'category_list.*' => 'required|integer',

        ];
        return $rules;
    }
}
