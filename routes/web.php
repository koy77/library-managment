<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookIssueController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('books.index'));

Route::resource('authors', AuthorController::class)->except(['show']);
Route::resource('books', BookController::class)->except(['show']);
Route::prefix('book-issues')->name('book-issues.')->group(function () {
    Route::get('/', [BookIssueController::class, 'index'])->name('index');
    Route::post('/', [BookIssueController::class, 'store'])->name('store');
    Route::patch('{issue}/return', [BookIssueController::class, 'return'])->name('return');
});
