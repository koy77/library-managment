<?php

namespace App\Actions;

use App\Models\BookIssue;
use Illuminate\Support\Facades\DB;

class ReturnBookAction
{
    /**
     * Возврат книги — удаление записи о выдаче.
     * Доступные экземпляры освобождаются автоматически (считаются от оставшихся записей).
     */
    public function execute(BookIssue $issue): void
    {
        DB::transaction(function () use ($issue) {
            BookIssue::whereKey($issue->id)->lockForUpdate()->firstOrFail()->delete();
        });
    }
}