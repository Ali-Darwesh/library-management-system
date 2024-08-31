<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class StoreBorrowRecordRequest extends FormRequest
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
            'book_id' => 'required|integer|exists:books,id',
            'borrowed_at' => 'required|date|before_or_equal:today', // Ensure it's today or before
            'due_date' => 'required|date|after:borrowed_at',        // Ensure it's after the borrowed_at date
        ];
    }
    protected function prepareForValidation()
    {
        $borrowedAt = $this->borrowed_at ? Carbon::parse($this->borrowed_at) : Carbon::now(); // or use $request->borrowed_at if provided
        $dueDate = $borrowedAt->copy()->addDays(14);
        $this->merge([
            'book_id' => $this->book_id,
            'user_id' => Auth::id(),
            'borrowed_at' => $borrowedAt->toDateString(),
            'due_date' => $dueDate->toDateString(),
            'returned_at' => null,
        ]);
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => 'please input data with correct form',
            'errors' => $validator->errors(),
        ]));
    }
}
