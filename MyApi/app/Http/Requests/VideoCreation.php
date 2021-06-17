<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class VideoCreation extends FormRequest
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
            'name' => 'string',
            'source' => 'file|required_without:name|file|mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4',
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
