<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class OrderPackageRequest extends FormRequest
{

    private $package;

    private $order;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $package = service('OrderPackage', $this->input('package_id'));
        if (!$package->hasItem()) {
            return false;
        }

        $order = service('Order', $package->order_id);
        if (!$order->hasItem()) {
            return false;
        }

        if ($order->vendor_id != Auth::id()) {
            return false;
        }

        $this->package = $package;
        $this->order = $order;


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
            'shipment_id' => 'required|integer',
            'tracking_number' => 'nullable|string|max:50',
            'tracking_status' => 'nullable|integer',
            'width' => 'required|numeric|between:0,999999.99',
            'height' => 'required|numeric|between:0,999999.99',
            'length' => 'required|numeric|between:0,999999.99',
            'weight' => 'required|numeric|between:0,999999.99',
            'desi' => 'nullable|numeric|between:0,999999.99',
            'calculated_desi' => 'nullable|numeric|between:0,999999.99',
            'description' => 'required|string|max:45',
        ];

        return $rules;
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->pickupDateCheck()) {
                $validator->errors()->add('field', 'Change pickup date.');
            }
            if ($this->isWeekend()) {
                $validator->errors()->add('field', 'Pickup date can not be at Weekend.');
            }
        });
    }

    /**
     * @return bool
     */
    protected function pickupDateCheck(): bool
    {
        if ($this->order->shipped_at < date("Y-m-d", strtotime(date("Y-m-d") . " -10 days"))) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    protected function isWeekend(): bool
    {
        $weekDay = date('w', strtotime($this->order->shipped_at));
        return ($weekDay == 0 || $weekDay == 6);
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @return mixed
     */
    public function getPackage()
    {
        return $this->package;
    }
}
