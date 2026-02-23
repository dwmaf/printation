@echo off
echo Menjalankan Printation Services...

cd c:\laragon\www\print-app

:: Start Laravel Web Server di background log minimal
start "Printation Web Server" /MIN php artisan serve --host=0.0.0.0 --port=8000

:: Start Reverb WebSocket di background
start "Printation WebSocket" /MIN php artisan reverb:start --host=0.0.0.0 --port=8080

:: (Opsional) Langsung buka Google Chrome Kiosk Mode ke halaman print station
:: start chrome --kiosk "http://localhost:8000/station"

echo Services berhasil dijalankan!
exit
