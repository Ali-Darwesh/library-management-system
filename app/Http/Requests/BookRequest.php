<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BookRequest extends FormRequest
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
            'title' => 'required|string|max:255|unique:books,title',
            'author' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'published_at' => 'required|date|before_or_equal:today',
            'category' => 'required|string|max:255',
            'is_available' => 'nullable|boolean'
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => 'please input data with correct form',
            'errors' => $validator->errors(),
        ]));
    }
    /**
     * first name
     * last name
     * =>user name
     */
    // protected function passedValidation()
    // {
    //     $this->merge([
    //         'user_name' => $this->input('first_name') . '_' . $this->input('last_name')
    //     ]);
    // }
    public function attributes()
    {
        return [
            'title' => 'book title',
        ];
    }
    public function messages()
    {
        return [
            'required' => 'the :attribute field must br writen',
        ];
    }
}
