#!/bin/bash
set -euo pipefail

echo ">>> Setting up operational infrastructure for inventory-sys-pro..."

# --- Backup directory ---
echo "  Creating backup directory..."
sudo mkdir -p /srv/backups/inventory-sys-pro
sudo chown -R ubuntu:ubuntu /srv/backups/inventory-sys-pro

# --- Crontab entries ---
echo "  Adding crontab entries..."
CRON_BACKUP="30 3 * * * /srv/apps/inventory-sys-pro/scripts/backup.sh >> /srv/backups/inventory-sys-pro/backup-cron.log 2>&1"
CRON_HEALTH="*/5 * * * * /srv/apps/inventory-sys-pro/scripts/healthcheck.sh >> /srv/backups/inventory-sys-pro/healthcheck-cron.log 2>&1"

(crontab -l 2>/dev/null | grep -v "inventory-sys-pro/scripts/backup.sh" || true; echo "$CRON_BACKUP") | crontab -
(crontab -l 2>/dev/null | grep -v "inventory-sys-pro/scripts/healthcheck.sh" || true; echo "$CRON_HEALTH") | crontab -

echo "  Current crontab:"
crontab -l

# --- UFW deny rule for port 10011 ---
echo "  Adding UFW deny rule for port 10011..."
existing=$(sudo ufw status | grep -c "10011" || true)
if [ "$existing" -eq 0 ]; then
    sudo ufw deny 10011
    echo "  UFW rule added."
else
    echo "  UFW rule for 10011 already exists, skipping."
fi

# --- Swap (4G) ---
SWAPFILE="/swapfile"
if [ ! -f "$SWAPFILE" ]; then
    echo "  Creating 4GB swap file..."
    sudo fallocate -l 4G "$SWAPFILE"
    sudo chmod 600 "$SWAPFILE"
    sudo mkswap "$SWAPFILE"
    sudo swapon "$SWAPFILE"
    echo "  Adding swap to fstab..."
    if ! grep -q "$SWAPFILE" /etc/fstab; then
        echo "$SWAPFILE none swap sw 0 0" | sudo tee -a /etc/fstab
    fi
    echo "  Swap configured."
else
    echo "  Swap already exists, skipping."
fi

echo ""
echo ">>> Setup complete!"
echo "    Backups:  /srv/backups/inventory-sys-pro/"
echo "    Cron:     backup (3:30 AM daily), healthcheck (every 5 min)"
echo "    UFW:      port 10011 denied"
echo "    Swap:     $(free -h | awk '/Swap/{print $2}') total"