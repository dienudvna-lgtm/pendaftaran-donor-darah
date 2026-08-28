#!/bin/bash
# =============================================
# Automated Backup Script - Pendaftaran Donor Darah
# Untuk dijalankan di server Debian (via cron)
# =============================================

# --- KONFIGURASI (sesuaikan kalau perlu) ---
DB_USER="root"
DB_PASS=""
DB_NAME="pmi_connect"
BACKUP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/backups"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
BACKUP_FILE="$BACKUP_DIR/backup_${DB_NAME}_${TIMESTAMP}.sql"

# --- PROSES BACKUP ---
mkdir -p "$BACKUP_DIR"

if [ -z "$DB_PASS" ]; then
    mysqldump -u "$DB_USER" "$DB_NAME" > "$BACKUP_FILE"
else
    mysqldump -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" > "$BACKUP_FILE"
fi

if [ $? -eq 0 ]; then
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] Backup berhasil: $BACKUP_FILE"
else
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] Backup GAGAL"
    exit 1
fi

# --- HAPUS BACKUP LAMA (lebih dari 7 hari), biar tidak penuh disk ---
find "$BACKUP_DIR" -name "backup_*.sql" -type f -mtime +7 -delete
