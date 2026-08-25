<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BookIssue;
use Illuminate\Database\Seeder;

class BookIssueSeeder extends Seeder
{
    public function run(): void
    {
        $readers = [
            'Иван Петров', 'Ольга Смирнова', 'Дмитрий Козлов', 'Екатерина Морозова',
            'Сергей Волков', 'Анна Соколова', 'Павел Новиков', 'Наталья Лебедева',
            'Алексей Фёдоров', 'Мария Орлова', 'Виктор Захаров', 'Татьяна Белова',
            'Григорий Павлов', 'Юлия Киселёва', 'Роман Гусев', 'Елена Тихонова',
            'Николай Кузнецов', 'Ирина Соловьёва', 'Максим Фролов', 'Дарья Ефимова',
        ];

        $books = Book::all();

        // Счётчик выданных экземпляров по книгам: возврат удаляет запись,
        // поэтому каждая запись о выдаче занимает один экземпляр.
        $issuedByBook = [];

        $create = function (Book $book, string $reader, \DateTimeInterface $issuedAt) use (&$issuedByBook) {
            $issuedByBook[$book->id] = ($issuedByBook[$book->id] ?? 0) + 1;

            BookIssue::create([
                'book_id' => $book->id,
                'reader_name' => $reader,
                'issued_at' => $issuedAt,
                'due_date' => fake()->dateTimeBetween($issuedAt, '+2 months'),
            ]);
        };

        // Выбирает случайную книгу со свободными экземплярами.
        $pickBook = function () use ($books, $issuedByBook) {
            return $books
                ->filter(fn (Book $b) => ($issuedByBook[$b->id] ?? 0) < $b->total_copies)
                ->random();
        };

        foreach ($readers as $reader) {
            $book = $pickBook();
            if (!$book) {
                break;
            }

            $create($book, $reader, fake()->dateTimeBetween('-4 months', 'now'));
        }

        for ($i = 0; $i < 25; $i++) {
            $book = $pickBook();
            if (!$book) {
                break;
            }

            $create($book, fake()->name('ru_RU'), fake()->dateTimeBetween('-6 months', 'now'));
        }
    }
}