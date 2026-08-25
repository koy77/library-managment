@extends('layouts.app')

@section('title', 'Выдачи книг')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Выдачи книг</h1>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#issueModal">Новая выдача</button>
    </div>

    <x-filter-block :action="route('book-issues.index')" buttons-class="col-12 col-lg-2">
        <div class="col-md-4 col-lg-3">
            <input type="text" class="form-control" name="reader_name" placeholder="Читатель" value="{{ request('reader_name') }}">
        </div>
        <div class="col-md-3 col-lg-2">
            <select name="book_id" class="form-select">
                <option value="">Все книги</option>
                @foreach ($books as $book)
                    <option value="{{ $book->id }}" @selected(request('book_id') == $book->id)>{{ $book->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <input type="date" class="form-control" name="issued_at" value="{{ request('issued_at') }}">
        </div>
        <div class="col-md-3 col-lg-2">
            <select name="status" class="form-select">
                <option value="">Все статусы</option>
<option value="active" @selected(request('status') === 'active')>Выдана</option>
                        <option value="overdue" @selected(request('status') === 'overdue')>Просрочена</option>
            </select>
        </div>
    </x-filter-block>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-cards">
                <thead class="table-light">
                    <tr>
                        <th>Книга</th>
                        <th>Читатель</th>
                        <th>Выдана</th>
                        <th>Срок возврата</th>
                        <th>Статус</th>
                        <th class="text-end">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($issues as $issue)
                        <tr>
                            <td data-label="Книга">
                                <strong>{{ $issue->book->title }}</strong>
                                <br><small class="text-muted">{{ $issue->book->author->full_name }}</small>
                            </td>
                            <td data-label="Читатель">{{ $issue->reader_name }}</td>
                            <td data-label="Выдана">{{ $issue->issued_at->format('d.m.Y') }}</td>
                            <td data-label="Срок возврата">{{ $issue->due_date->format('d.m.Y') }}</td>
                            <td data-label="Статус">
                                @if ($issue->status === App\Models\BookIssue::STATUS_ACTIVE)
                                    <span class="badge bg-warning text-dark">Выдана</span>
                                @else
                                    <span class="badge bg-danger">Просрочена</span>
                                @endif
                            </td>
                            <td data-label="Действия" class="text-end">
                                <form action="{{ route('book-issues.return', $issue) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-outline-success confirm-return">Вернуть</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Выдачи не найдены.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3 d-flex justify-content-center">
        {{ $issues->links() }}
    </div>

    <div class="modal fade" id="issueModal" tabindex="-1" @if($errors->any())data-bs-backdrop="static"@endif>
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('book-issues.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Выдать книгу</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="mb-3">
                            <label for="book_id" class="form-label">Книга (доступные)</label>
                            <select class="form-select @error('book_id') is-invalid @enderror" id="book_id" name="book_id" required>
                                <option value="">Выберите книгу</option>
                                @foreach ($availableBooks as $id => $title)
                                    <option value="{{ $id }}" @selected(old('book_id') == $id)>{{ $title }}</option>
                                @endforeach
                            </select>
                            @error('book_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="reader_name" class="form-label">Имя читателя</label>
                            <input type="text" class="form-control @error('reader_name') is-invalid @enderror"
                                   id="reader_name" name="reader_name" value="{{ old('reader_name') }}" required>
                            @error('reader_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="due_date" class="form-label">Дата возврата</label>
                            <input type="date" class="form-control @error('due_date') is-invalid @enderror"
                                   id="due_date" name="due_date" value="{{ old('due_date') }}" required>
                            @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-primary">Выдать</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            @if ($errors->any())
                new bootstrap.Modal(document.getElementById('issueModal')).show();
            @endif

            $('.confirm-return').on('click', function (e) {
                if (!confirm('Подтвердить возврат книги?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endpush
