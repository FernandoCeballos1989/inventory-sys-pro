#!/bin/bash
set -euo pipefail

APP_NAME="inventory-sys-pro"
APP_DIR="/srv/apps/${APP_NAME}"
BACKUP_DIR="/srv/backups/${APP_NAME}"
COMPOSE_FILES="-f compose.yaml -f compose.prod.yaml"
RETENTION_DAYS=14

umask 077

mkdir -p "${BACKUP_DIR}"

TIMESTAMP=$(date +%Y%m%d_%H%M%S)
BACKUP_PATH="${BACKUP_DIR}/${TIMESTAMP}"
mkdir -p "${BACKUP_PATH}"

echo "[$(date -Iseconds)] Starting backup for ${APP_NAME}..."

# --- SQLite database ---
echo "  Dumping SQLite database..."
cd "${APP_DIR}"
docker compose ${COMPOSE_FILES} exec -T laravel.test sqlite3 /var/www/html/database/database.sqlite ".dump" > "${BACKUP_PATH}/database.sql"
gzip "${BACKUP_PATH}/database.sql"

# --- .env ---
echo "  Backing up .env..."
cp "${APP_DIR}/.env" "${BACKUP_PATH}/env"

# --- Storage volume ---
echo "  Backing up storage volume..."
STORAGE_SRC=$(docker compose ${COMPOSE_FILES} exec -T laravel.test realpath /var/www/html/storage 2>/dev/null | tr -d '\r' || echo "")
if [ -n "${STORAGE_SRC}" ]; then
    docker run --rm -v "${APP_NAME}_app-storage:/data:ro" -v "${BACKUP_PATH}:/backup" alpine tar czf /backup/storage.tar.gz -C /data .
fi

# --- Set permissions ---
chmod 600 "${BACKUP_PATH}"/*
chmod 700 "${BACKUP_PATH}"

# --- Cleanup old backups ---
echo "  Cleaning backups older than ${RETENTION_DAYS} days..."
find "${BACKUP_DIR}" -maxdepth 1 -type d -mtime +${RETENTION_DAYS} -exec rm -rf {} +

SIZE=$(du -sh "${BACKUP_PATH}" 2>/dev/null | cut -f1 || echo "unknown")
echo "[$(date -Iseconds)] Backup complete: ${BACKUP_PATH} (${SIZE})"