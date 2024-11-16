<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class AuthFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    // public function authorize(): bool
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'email'=> 'required|email|unique:users,email',
            'password' => ['string','min:8','required'],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $flatMapErrors = collect($validator->errors())->flatMap(function($e,$field){
            return [$field => $e[0]];
        });

        throw new HttpResponseException(response()->json([
            'status'   => 422,
            'message'   => 'Validation errors',
            'data'      => $flatMapErrors
        ],422));
    }
}
