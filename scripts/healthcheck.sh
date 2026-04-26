#!/bin/bash
set -euo pipefail

APP_NAME="inventory-sys-pro"
APP_DIR="/srv/apps/${APP_NAME}"
COMPOSE_FILES="-f compose.yaml -f compose.prod.yaml"
DOMAIN="https://inventory.shorai-lab.com"
DISK_THRESHOLD=90
LOG="/srv/backups/${APP_NAME}/healthcheck-cron.log"

mkdir -p "/srv/backups/${APP_NAME}"

ALERT=""

# --- Container status ---
cd "${APP_DIR}"
CONTAINER_STATUS=$(docker compose ${COMPOSE_FILES} ps --format json 2>/dev/null | python3 -c "
import sys, json
for line in sys.stdin:
    try:
        obj = json.loads(line.strip())
        health = obj.get('Health', obj.get('status', 'unknown'))
        state = obj.get('State', 'unknown')
        if state != 'running' or (health and health not in ('healthy', 'starting')):
            print(f'ALERT: {obj.get(\"Name\",\"?\")} state={state} health={health}')
    except: pass
" 2>/dev/null || echo "ALERT: could not parse container status")

if [ -n "${CONTAINER_STATUS}" ]; then
    ALERT="${ALERT}\n${CONTAINER_STATUS}"
fi

# --- HTTPS response ---
HTTP_CODE=$(curl -sf -o /dev/null -w '%{http_code}' --max-time 10 "${DOMAIN}" 2>/dev/null || echo "000")
if [ "${HTTP_CODE}" -lt 200 ] || [ "${HTTP_CODE}" -ge 500 ]; then
    ALERT="${ALERT}\nALERT: ${DOMAIN} returned HTTP ${HTTP_CODE}"
fi

# --- Disk usage ---
DISK_PCT=$(df / | tail -1 | awk '{print $5}' | tr -d '%')
if [ "${DISK_PCT}" -ge "${DISK_THRESHOLD}" ]; then
    ALERT="${ALERT}\nALERT: disk usage at ${DISK_PCT}% (threshold: ${DISK_THRESHOLD}%)"
fi

# --- Report ---
if [ -n "${ALERT}" ]; then
    echo -e "[$(date -Iseconds)] ISSUES DETECTED for ${APP_NAME}:${ALERT}" >> "${LOG}"
else
    echo "[$(date -Iseconds)] ${APP_NAME} healthy | HTTP=${HTTP_CODE} | Disk=${DISK_PCT}%" >> "${LOG}"
fi