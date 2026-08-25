<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['author_id', 'title', 'publication_year', 'isbn', 'total_copies'];

    protected $appends = ['available_copies'];

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function bookIssues(): HasMany
    {
        return $this->hasMany(BookIssue::class);
    }

    public function getAvailableCopiesAttribute(): int
    {
        return $this->total_copies - $this->active_issues_count;
    }

    public function getTitleWithAuthorAttribute(): string
    {
        return "{$this->title} — {$this->author->full_name}";
    }

    public function scopeWithAvailableCopies($query)
    {
        // Все записи о выдаче занимают экземпляры: возврат удаляет запись.
        return $query->withCount('bookIssues as active_issues_count');
    }

    public function scopeFilter($query, array $filters)
    {
        return $query
            ->when($filters['title'] ?? null, fn ($q, $t) => $q->where('title', 'ilike', "%{$t}%"))
            ->when($filters['author_id'] ?? null, fn ($q, $a) => $q->where('author_id', $a))
            ->when($filters['isbn'] ?? null, fn ($q, $i) => $q->where('isbn', 'ilike', "%{$i}%"));
    }
}
