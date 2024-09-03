<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class UpdateBorrowRecordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();
        // Ensure that there is an authenticated user
        if (!$user || (!$user->is_admin && $this->user()->id !== $user->id)) {
            abort(response()->json([
                'error' => 'You are not authorized to perform this action.',
            ], 403));
        }

        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->returned_at) {
            $borrow_record = $this->route('borrow_record'); // Assuming you have route model binding for borrow_record

            $this->merge([
                'book_id' => $borrow_record->book_id,
                'borrowed_at' => $borrow_record->borrowed_at,
                'returned_at' => Carbon::parse($this->returned_at)
            ]);
        }
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'book_id' => 'sometimes|integer|exists:books,id',
            'borrowed_at' => 'sometimes|date|before_or_equal:today',
            'returned_at' => 'nullable|date|after_or_equal:borrowed_at',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => 'please input data with correct form ',
            'errors' => $validator->errors(),
        ]));
    }
    protected function passedValidation()
    {
        //route model binding for borrow_record
        $borrow_record = $this->route('borrow_record');
        $borrowedAt = Carbon::parse($borrow_record->borrowed_at);
        $dueDate = Carbon::parse($borrow_record->due_date);

        // Calculate the difference in days between borrowed_at and due_date
        $daysDifference = $borrowedAt->diffInDays($dueDate);
        $message = '';

        if ($this->returned_at) {
            // Check if the book was returned late
            // Check if the returned date is on or before the due date
            if ($daysDifference > 14) {
                $message = 'The book was returned late.';
            } else {
                $message = 'The book was returned.';
            }
            $borrow_record->update([
                'returned_at' => $this->returned_at,
            ]);
        } elseif ($daysDifference > 14) {

            $due_date = $dueDate->copy()->addDays(3);
            $message = 'The book was not returned in the specified period. Please return it within three days.';

            $borrow_record->update([
                'due_date' => $due_date,
            ]);
        } else {
            $message = 'The book was not returned';
        }
        $this->merge([
            'message' => $message
        ]);
    }
}
