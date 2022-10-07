<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{

    /**
     * Indicates whether validation should stop after the first rule failure.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = false;

    protected $userId;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->userId = match ($this->route()->uri) {
            'register' => $this->route('admin_user'), // Register page
            'dashboard' => Auth::id(), // User dashboard update
            'admin/admin_users/{admin_user}' => $this->route('admin_user'), // Admin update
        };

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $emailRules = [
            'required',
            'email',
            'max:100',
            Rule::unique('users')->ignore($this->userId)
        ];

        $rules = [
            'id' => 'nullable|integer',
            'name' => 'required|string|max:100',
            'surname' => 'required|string|max:100',
            'user_type' => 'required|in:0,1',
            'identity' => [
                'required_if:user_type,=,0',
                'nullable',
                'integer',
            ],

            'company_owner' => 'required_if:user_type,=,1|nullable|string|max:100',
            'company_name' => 'required_if:user_type,=,1|nullable|string|max:100',
            'company_tax' => 'required_if:user_type,=,1|nullable|string|max:100',
            'company_taxid' => 'required_if:user_type,=,1|nullable|string|max:100',

            'bank' => 'nullable|string|max:100',

            'email' => $emailRules,
            'permission_id' => 'integer',
            'user_group_id' => 'integer',
            'lang' => 'integer',

            'is_same_address' => 'integer|in:1,0',
            'warehouse_address' => 'string|max:255',
            'warehouse_postal_code' => 'string|max:10|nullable',
            'warehouse_state_id' => 'integer',
            'warehouse_city_id' => 'integer',
            'warehouse_phone' => 'string|nullable|max:50',
            'warehouse_closed_at' => 'date_format:H:i|nullable',
            'warehouse_location' => 'string|nullable|max:80',

            'invoice_address' => 'required_if:is_same_address,=,0|string|max:255|nullable',
            'invoice_postal_code' => 'string|max:10|nullable',
            'invoice_state_id' => 'required_if:is_same_address,=,0|integer|nullable',
            'invoice_city_id' => 'required_if:is_same_address,=,0|integer|nullable',
            'invoice_phone' => 'string|nullable|max:50',
        ];

        return $rules;

    }
}
