<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use function service;

class PermissonRequest extends FormRequest
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
            'name' => 'required|string|max:50',
        ];

        $service = service('Permission');
        foreach ($service::getPermissionArray() as $permission => $val) {
            $rules[$permission] = 'required';
        }

        return $rules;
    }
}
