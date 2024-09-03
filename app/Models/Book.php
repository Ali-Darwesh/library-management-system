<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

class Book extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'author',
        'description',
        'published_at',
        'category',
        'is_available'
    ];
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }
    public function borrow_records(): HasMany
    {
        return $this->hasMany(Borrow_record::class);
    }
    public function scopeFilter(Builder $query, $filters)
    {
        if (!empty($filters['author'])) {
            $query->where('author', 'like', '%' . $filters['author'] . '%');
        }
        if (isset($filters['is_available'])) {
            // Output the value of 'is_available' for debugging

            // Convert '0' and 'false' to false, and '1' and 'true' to true
            $isAvailable = filter_var($filters['is_available'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

            // Apply the filter only if the value is not null
            if (!is_null($isAvailable)) {
                $query->where('is_available', $isAvailable);
            }
        }
        if (!empty($filters['category'])) {
            $query->where('category', 'like', '%' . $filters['category'] . '%');
        }


        return $query;
    }
    /**
     * Scope to sort on release_year ordered by sortOrder
     * @param Builder $query, $sortBy, $sortOrder
     *
     */
    public function scopeSort(Builder $query, $sortBy, $sortOrder)
    {
        if ($sortBy) {
            return $query->orderBy($sortBy, $sortOrder);
        }
        return $query;
    }
}
