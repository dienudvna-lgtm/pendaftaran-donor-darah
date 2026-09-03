@echo off
REM =============================================
REM Automated Backup Script - Pendaftaran Donor Darah
REM Untuk testing lokal di Windows (XAMPP)
REM =============================================

setlocal enabledelayedexpansion

REM --- KONFIGURASI (sesuaikan path XAMPP kalau beda) ---
set MYSQLDUMP="C:\xampp\mysql\bin\mysqldump.exe"
set DB_USER=root
set DB_NAME=pmi_connect
set BACKUP_DIR=%~dp0backups

REM --- Buat timestamp aman (tanpa karakter aneh) ---
for /f "tokens=1-4 delims=/ " %%a in ('date /t') do (set mydate=%%c%%a%%b)
for /f "tokens=1-2 delims=: " %%a in ('time /t') do (set mytime=%%a%%b)
set TIMESTAMP=%mydate%_%mytime%

if not exist "%BACKUP_DIR%" mkdir "%BACKUP_DIR%"

%MYSQLDUMP% -u %DB_USER% %DB_NAME% > "%BACKUP_DIR%\backup_%DB_NAME%_%TIMESTAMP%.sql"

if %ERRORLEVEL% EQU 0 (
    echo Backup berhasil: backups\backup_%DB_NAME%_%TIMESTAMP%.sql
) else (
    echo Backup GAGAL. Cek path mysqldump.exe atau nama database.
)

pause
