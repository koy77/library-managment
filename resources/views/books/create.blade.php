@extends('layouts.app')

@section('title', 'Добавить книгу')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Добавить книгу</h1>
        <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">Назад</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('books.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-8">
                        <label for="title" class="form-label">Название</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                               id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="author_id" class="form-label">Автор</label>
                        <select class="form-select @error('author_id') is-invalid @enderror" id="author_id" name="author_id" required>
                            <option value="">Выберите автора</option>
                            @foreach ($authors as $author)
                                <option value="{{ $author->id }}" @selected(old('author_id') == $author->id)>{{ $author->full_name }}</option>
                            @endforeach
                        </select>
                        @error('author_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="publication_year" class="form-label">Год издания</label>
                        <input type="number" class="form-control @error('publication_year') is-invalid @enderror"
                               id="publication_year" name="publication_year" value="{{ old('publication_year') }}"
                               min="1" max="{{ date('Y') }}" required>
                        @error('publication_year')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="isbn" class="form-label">ISBN</label>
                        <input type="text" class="form-control @error('isbn') is-invalid @enderror"
                               id="isbn" name="isbn" value="{{ old('isbn') }}" required>
                        @error('isbn')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="total_copies" class="form-label">Всего экземпляров</label>
                        <input type="number" class="form-control @error('total_copies') is-invalid @enderror"
                               id="total_copies" name="total_copies" value="{{ old('total_copies', 1) }}" min="0" required>
                        @error('total_copies')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Сохранить</button>
            </form>
        </div>
    </div>
@endsection
