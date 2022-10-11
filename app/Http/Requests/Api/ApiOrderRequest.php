<?php

namespace App\Http\Requests\api;

use http\Env\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class ApiOrderRequest extends FormRequest
{
    /**
     * @var
     */
    protected $user;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->validateUser()) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        //dd(request()->createDate);
        return [
            'id' => 'required|integer',
            'currency' => 'required|in:USD,EUR,TL,TRY',
            'createDate' => 'required|date_format:Y-m-d H:i:s',
            'requestDate' => 'required|date_format:Y-m-d H:i:s',
            'requestId' => 'nullable',

            'items' => 'required|array',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer',
            'items.*.unitPrice' => 'required|numeric|between:0,99999999.99',
            'items.*.gtipCode' => 'required|string',
        ];
    }

    /**
     * @return bool
     */
    private function validateUser(): bool
    {
        $authorizationHeader = request()->header('Authorization');
        $authorizationHeader = str_replace('Basic ', '', $authorizationHeader);
        $authorizationHeader = base64_decode($authorizationHeader);
        $parts = explode(':', $authorizationHeader);
        list($user, $pass) = $parts;
        if (!is_null($this->user = User::where('api_pass', $pass)->where('email', $user)->first())) {
            return true;
        }
        return false;
    }

    /**
     * @return string|null
     */
    public function getRequestUser()
    {
        return $this->user;
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    { // Put whatever response you want here.
        return new \JsonResponse([
            'status' => '422',
            'errors' => $errors,
        ], 422);
      /*  echo response()->json($validator->errors()->toJson(JSON_PRETTY_PRINT));

        exit;
      */
    }

    public function response(array $errors) {

        // Put whatever response you want here.
        return new JsonResponse([
            'status' => '422',
            'errors' => $errors,
        ], 422);
    }

}
