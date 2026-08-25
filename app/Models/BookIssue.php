<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookIssue extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_OVERDUE = 'overdue';

    protected $fillable = ['book_id', 'reader_name', 'issued_at', 'due_date'];

    protected $casts = [
        'issued_at' => 'date',
        'due_date' => 'date',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Статус вычисляется: выдана (срок не истёк) или просрочена.
     * Возвращённые выдачи в таблице не хранятся — запись удаляется при возврате.
     */
    public function getStatusAttribute(): string
    {
        return $this->due_date->lt(today()) ? self::STATUS_OVERDUE : self::STATUS_ACTIVE;
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['reader_name'] ?? null, fn ($q, $r) => $q->where('reader_name', 'ilike', "%{$r}%"))
            ->when($filters['book_id'] ?? null, fn ($q, $b) => $q->where('book_id', $b))
            ->when($filters['issued_at'] ?? null, fn ($q, $d) => $q->whereDate('issued_at', $d))
            ->when($filters['status'] ?? null, fn ($q, $s) => $q->whereDate(
                'due_date',
                $s === self::STATUS_OVERDUE ? '<' : '>=',
                today(),
            ));
    }

    public function scopeWithSearch(Builder $query): Builder
    {
        return $query->with(['book.author:id,first_name,last_name']);
    }
}