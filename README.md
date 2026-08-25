# Система управления библиотекой

Веб-приложение на **Laravel 12** для управления библиотекой: авторы, книги и выдачи книг читателям.
Стек: **PHP 8.3 + FrankenPHP** (классический режим, не worker), **Laravel 12**, **PostgreSQL 16**, **Adminer**, **Bootstrap 5 + jQuery**.

## Запуск

Требуется Docker + Docker Compose.

**Первый запуск (холодный старт):**

```bash
git clone <url> library-management
cd library-management
./start.sh
```

`start.sh` делает всё сам: создаёт `.env`, собирает образы, поднимает стек (app + db + adminer + vite), дожидается готовности БД, прогоняет миграции и сиды.

**Последующие запуски** — с горячей перезагрузкой (Vite HMR, Blade/CSS/JS обновляются на лету):

```bash
./watch.sh
```

## Доступ

- Приложение: http://localhost:8083
- Adminer: http://localhost:8084 (Система: PostgreSQL | Сервер: db | Пользователь: laravel | Пароль: secretpassword | БД: library)
