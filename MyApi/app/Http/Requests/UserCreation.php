<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;

class UserCreation extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|string|regex:/^[a-zA-Z0-9_-]*$/',
            'pseudo' => 'string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string'
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     *
     * @throws HttpResponseException
     */
    protected function failedValidation($validator)
    {
        $response = [
            'message' => 'Bad Request',
            'code' => '10001',
            'data' => $validator->errors(),
        ];

        throw new HttpResponseException(response()->json($response, 400));
    }
}
