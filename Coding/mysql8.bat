@echo off
title MySQL (Single Console)

:: Path MySQL dan Laravel
set MYSQL_PATH=C:\mysql\bin
set LARAVEL_PATH=D:\zproject\tnl_dev
set PHP_EXE=php7

echo ========================================
echo  Starting MySQL 8.0 Server (Foreground)
echo ========================================
cd /d %MYSQL_PATH%

:: Jalankan MySQL di background tapi biar output tetap kelihatan
start "" /b cmd /c "%MYSQL_PATH%\mysqld --console"

:: Tunggu 3 detik biar MySQL siap
timeout /t 3 /nobreak >nul

echo Done.
pause
