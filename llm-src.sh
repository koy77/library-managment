#!/usr/bin/env bash
#
# llm-src.sh — собирает исходники в один файл library-managment.txt для LLM.
# Порядок: сначала .md (README) в начале, затем папки app/, routes/, config/, resources/.
#
set -euo pipefail
cd "$(dirname "$0")"

OUT="library-managment.txt"
: > "$OUT"

# Маска (текстовые исходники)
mask=( \( -name '*.php' -o -name '*.blade.php' -o -name '*.css' -o -name '*.js' \
        -o -name '*.json' -o -name '*.yml' -o -name '*.yaml' \) )

count=0

# Функция: добавить файл с заголовком
append_file() {
    if LC_ALL=C grep -qI . "$1" 2>/dev/null; then :; else return; fi
    {
        echo ""
        echo "======================================================================"
        echo "FILE: ${1#./}"
        echo "======================================================================"
        cat "$1"
    } >> "$OUT"
    count=$((count + 1))
}

# 1) .md файлы (README.md и др.) — в начале
while IFS= read -r f; do
    append_file "$f"
done < <(find . -maxdepth 1 -type f -name '*.md' 2>/dev/null | sort)

# 2) Папки: app, routes, config, resources
for d in 'app' 'routes' 'config' 'resources'; do
    [ -d "$d" ] || continue
    while IFS= read -r f; do
        append_file "$f"
    done < <(find "$d" -type f "${mask[@]}" 2>/dev/null | sort)
done

echo "Собрано файлов: $count"
echo "Результат: $OUT ($(du -h "$OUT" | cut -f1))"