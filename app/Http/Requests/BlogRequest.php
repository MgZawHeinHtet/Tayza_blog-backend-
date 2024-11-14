<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Collection;

class BlogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required',
            'description' => 'required',
            'photo' => 'required',
            'category_id' => 'required',
            'user_id' => 'required',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $flatMapErrors = collect($validator->errors())->flatMap(function($e,$field){
            return [$field => $e[0]];
        });

            
        throw new HttpResponseException(response()->json([
            'status'   => 443,
            'message'   => 'Validation errors',
            'data'      => $flatMapErrors
        ],443));
    }
}
