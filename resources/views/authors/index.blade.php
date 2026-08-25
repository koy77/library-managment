@extends('layouts.app')

@section('title', 'Авторы')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Авторы</h1>
        <a href="{{ route('authors.create') }}" class="btn btn-success">Добавить автора</a>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-cards">
                <thead class="table-light">
                    <tr>
                        <th>Имя</th>
                        <th>Книг в библиотеке</th>
                        <th class="text-end">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($authors as $author)
                        <tr>
                            <td data-label="Имя">
                                <strong>{{ $author->full_name }}</strong>
                                <button type="button"
                                        class="btn btn-link btn-sm p-0 ms-2 author-toggle"
                                        data-target="#books-{{ $author->id }}">
                                    Показать книги
                                </button>
                            </td>
                            <td data-label="Книг в библиотеке">{{ $author->books->count() }}</td>
                            <td data-label="Действия" class="text-end text-nowrap">
                                <a href="{{ route('authors.edit', $author) }}" class="btn btn-sm btn-outline-primary">Редактировать</a>
                                <form action="{{ route('authors.destroy', $author) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger confirm-delete">Удалить</button>
                                </form>
                            </td>
                        </tr>
                        <tr class="author-books" id="books-{{ $author->id }}" style="display:none;">
                            <td colspan="3" class="p-0" data-label="">
                                <div class="p-3 bg-white border-top">
                                    @forelse ($author->books as $book)
                                        <div class="border-bottom py-1">
                                            <span>
                                                {{ $book->title }} ({{ $book->publication_year }})
                                                <span class="badge text-bg-light border ms-2">экз.: {{ $book->total_copies }}</span>
                                            </span>
                                        </div>
                                    @empty
                                        <div class="text-muted">У автора нет книг.</div>
                                    @endforelse
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted py-4">Авторы не найдены.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3 d-flex justify-content-center">
        {{ $authors->links() }}
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            $('.author-toggle').on('click', function () {
                var target = $(this).data('target');
                $(target).slideToggle(200);
                $(this).text($(target).is(':visible') ? 'Скрыть книги' : 'Показать книги');
            });

            $('.confirm-delete').on('click', function (e) {
                if (!confirm('Вы уверены, что хотите удалить запись?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endpush
