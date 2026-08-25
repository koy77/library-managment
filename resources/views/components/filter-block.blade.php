@props(['action', 'buttonsClass' => 'col-md-2'])

<div class="card shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" action="{{ $action }}" class="row g-2">
            {{ $slot }}

            <div class="{{ $buttonsClass }} d-flex gap-2 align-items-center">
                <button class="btn btn-primary flex-fill" type="submit">Фильтр</button>
                <a href="{{ $action }}" class="btn btn-outline-secondary d-flex align-items-center justify-content-center">Сброс</a>
            </div>
        </form>
    </div>
</div>