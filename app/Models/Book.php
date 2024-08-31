<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
