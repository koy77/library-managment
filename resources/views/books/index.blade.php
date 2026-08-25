@extends('layouts.app')

@section('title', 'Книги')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Книги</h1>
        <a href="{{ route('books.create') }}" class="btn btn-success">Добавить книгу</a>
    </div>

    <x-filter-block :action="route('books.index')">
        <div class="col-md-3 col-xl-5">
            <input type="text" class="form-control" name="title" placeholder="Название" value="{{ request('title') }}">
        </div>
        <div class="col-md-3">
            <select name="author_id" class="form-select">
                <option value="">Все авторы</option>
                @foreach ($authors as $author)
                    <option value="{{ $author->id }}" @selected(request('author_id') == $author->id)>{{ $author->full_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <input type="text" class="form-control" name="isbn" placeholder="ISBN" value="{{ request('isbn') }}">
        </div>
    </x-filter-block>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-cards">
                <thead class="table-light">
                    <tr>
                        <th>Название</th>
                        <th>Автор</th>
                        <th>Год</th>
                        <th>ISBN</th>
                        <th>Всего / Доступно</th>
                        <th class="text-end">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($books as $book)
                        <tr>
                            <td data-label="Название"><strong>{{ $book->title }}</strong></td>
                            <td data-label="Автор">{{ $book->author->full_name }}</td>
                            <td data-label="Год">{{ $book->publication_year }}</td>
                            <td data-label="ISBN"><code>{{ $book->isbn }}</code></td>
                            <td data-label="Всего / Доступно">
                                <span class="copies">
                                    <span class="badge bg-secondary">{{ $book->total_copies }}</span>
                                    /
                                    <span class="badge bg-{{ $book->available_copies > 0 ? 'success' : 'danger' }}">
                                        {{ $book->available_copies }}
                                    </span>
                                </span>
                            </td>
                            <td data-label="Действия" class="text-end text-nowrap">
                                <a href="{{ route('books.edit', $book) }}" class="btn btn-sm btn-outline-primary">Редактировать</a>
                                <form action="{{ route('books.destroy', $book) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger confirm-delete">Удалить</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Книги не найдены.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3 d-flex justify-content-center">
        {{ $books->links() }}
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            $('.confirm-delete').on('click', function (e) {
                if (!confirm('Вы уверены, что хотите удалить запись?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endpush
