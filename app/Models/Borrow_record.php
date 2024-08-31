<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Borrow_record extends Model
{
    use HasFactory;
    protected $fillable = [
        'book_id',
        'user_id',
        'borrowed_at',
        'due_date',
        'returned_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function scopeCheckIfReturned(Builder $query, $validatedData, Borrow_record $borrow_record)
    {
        $borrowedAt = Carbon::parse($borrow_record->borrowed_at);
        $dueDate = Carbon::parse($borrow_record->due_date);

        // Calculate the difference in days between borrowed_at and due_date
        $daysDifference = $borrowedAt->diffInDays($dueDate);
        $message = '';

        if ($validatedData->has('returned_at')) {
            $returnedAt = Carbon::parse($validatedData->returned_at);

            // Check if the book was returned late
            // Check if the returned date is on or before the due date
            if ($daysDifference > -1) {
                $message = 'The book was returned late.';
            }
            return ['due_date' => null, 'returned_at' => $returnedAt, 'message' => $message];
        } else {
            $today = Carbon::now();

            // Check if the current date is past the due date
            if ($daysDifference > -1) {
                $due_date = $dueDate->copy()->addDays(3);
                $message = 'The book was not returned in the specified period. Please return it within three days.';
            }

            return [
                'due_date' => $due_date,
                'returned_at' => null,
                'message' => $message,
            ];
        }
    }
}
