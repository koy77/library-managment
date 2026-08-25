#!/usr/bin/env bash
#
# start.sh — полное холодное развёртывание проекта одной командой:
#   - создаёт .env из .env.example при отсутствии
#   - останавливает существующие контейнеры и поднимает стек с пересборкой
#   - ждёт готовности PostgreSQL
#   - генерирует APP_KEY (если пуст), прогоняет миграции и сиды
#   - ждёт готовности приложения и Vite
#
set -euo pipefail
cd "$(dirname "$0")"

echo "==> 1. Окружение (.env)"
if [ ! -f .env ]; then
    cp .env.example .env
    echo "    создан .env из .env.example"
fi

echo "==> 2. Остановка существующих контейнеров"
docker compose down --remove-orphans

echo "==> 3. Сборка и запуск стека (app + db + adminer + vite)"
docker compose up -d --build

echo "==> 4. Ожидание готовности PostgreSQL..."
until docker compose exec -T db pg_isready -U laravel -d library >/dev/null 2>&1; do
    sleep 2
done
echo "    БД готова"

echo "==> 5. Ключ приложения"
if grep -qE '^APP_KEY=$' .env; then
    docker compose exec -T app php artisan key:generate --force
fi

echo "==> 6. Миграции и сиды"
docker compose exec -T app php artisan migrate:fresh --seed --force

echo "==> 7. Ожидание готовности приложения и Vite..."
until curl -sf http://localhost:5173/@vite/client -o /dev/null \
   && curl -sf http://localhost:8083/books -o /dev/null; do
    sleep 3
done

echo ""
echo "=================================================="
echo "  Готово!"
echo "  App:      http://localhost:8083"
echo "  Adminer:  http://localhost:8084  (PostgreSQL | db | laravel | secretpassword | library)"
echo "  Vite HMR: http://localhost:5173"
echo "=================================================="