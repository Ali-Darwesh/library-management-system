<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateBookRequest extends FormRequest
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
            'title' => 'sometimes|string|max:255|unique:books,title',
            'author' => 'sometimes|string|max:255|min:3',
            'description' => 'sometimes|string|max:255',
            'published_at' => 'sometimes|date|before_or_equal:today',
            'category' => 'sometimes|string|max:255',
        ];
    }
    /**
     * The failedValidation method is used to customize the response that is returned when form validation fails 
     * @param Validator $validator
     * it throws an HttpResponseException
     * @return \Illuminate\HTTP\JsonResponse
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => 'please input data with correct form',
            'errors' => $validator->errors(),
        ]));
    }

    public function attributes()
    {
        return [
            'title' => 'book title',
            'author' => 'author name',
            'description' => ' book description',
            "published_at" => 'book publication date'
        ];
    }
    public function messages()
    {
        return [
            'sometimes' => 'the :attribute field must be writen',
            'date' => 'the :attribute filed must writen in(( Year-Month-Day )) form'
        ];
    }
}
