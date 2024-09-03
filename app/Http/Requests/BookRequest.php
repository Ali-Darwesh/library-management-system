<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class BookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * just the admin can add books
     */
    public function authorize(): bool
    {
        return Auth::user()->is_admin;
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'author' => ucwords(strtolower($this->author)),

        ]);
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
            'author' => 'required|string|max:255|min:3',
            'description' => 'required|string|max:255|min:10',
            'published_at' => 'required|date|before_or_equal:today',
            'category' => 'required|string|max:255',
            'is_available' => 'nullable|boolean'
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => 'please input book data with correct form',
            'errors' => $validator->errors(),
        ]));
    }
    /**
     * change the way of displaying the attributes to be easy for user to understand
     */
    public function attributes()
    {
        return [
            'title' => 'book title',
            'author' => 'author name',
            'description' => ' book description',
            "published_at" => 'book publication date'
        ];
    }
    /**
     * specific the messages of each validate error
     */
    public function messages()
    {
        return [
            'required' => 'the :attribute field must be writen',
            'date' => 'the :attribute filed must writen in(( Year-Month-Day )) form'
        ];
    }
}
