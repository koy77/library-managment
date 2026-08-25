#!/usr/bin/env bash
#
# Запуск стека с Hot Reload:
#   - Vite HMR (порт 5173) обновляет Blade/CSS/JS на лету,
#     а также перезагружает страницу при изменении .php (refresh: true)
#   - PHP (классический режим FrankenPHP) подхватывает изменения при каждом запросе
#
set -euo pipefail
cd "$(dirname "$0")"

echo "==> Подтягивание образов и запуск контейнеров (app + db + adminer + vite)..."
docker compose up -d --build

echo ""
echo "=================================================="
echo "  App:     http://localhost:8083"
echo "  Adminer: http://localhost:8084"
echo "  Vite:    http://localhost:5173"
echo "  PostgreSQL: localhost:5434 (db:5432)"
echo ""
echo "  HOT RELOAD: правь .php / Blade / CSS / JS —"
echo "              браузер обновится сам, без перезапуска контейнеров."
echo "=================================================="
echo ""

# Логи обоих контейнеров в реальном времени
docker compose logs -f app vite
