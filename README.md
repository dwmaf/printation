# Print App - Installation Guide

Proyek ini adalah aplikasi berbasis Laravel. Ikuti langkah-langkah di bawah ini untuk menjalankan proyek di mesin lokal Anda.

## Prasyarat

Pastikan Anda sudah menginstal:
- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL/MariaDB (atau Laragon)

## Langkah Instalasi

### 1. Clone Repository
```sh
git clone https://github.com/dwmaf/print-app.git
cd print-app
```

### 2. Instal Dependensi PHP
```sh
composer install
```

### 3. Setup File Environment
Salin file `.env.example` menjadi `.env` dan sesuaikan konfigurasi database Anda.
```sh
copy .env.example .env
```
*Gunakan `cp` jika menggunakan terminal Git Bash/Linux.*

### 4. Generate Application Key
```sh
php artisan key:generate
```

### 5. Migrasi Database
Pastikan database sudah dibuat di MySQL, lalu jalankan:
```sh
php artisan migrate
```

### 6. Instal Dependensi Frontend
```sh
npm install
npm run build
```

### 7. Konfigurasi Reverb
Buka file `.env` Anda, pastikan variabel berikut sudah terisi dengan nilai di bawah ini untuk mendukung fitur *real-time*:

```dotenv
BROADCAST_CONNECTION=reverb

REVERB_APP_ID=821407
REVERB_APP_KEY=hieghuun6jbnpmgzqlhr
REVERB_APP_SECRET=9rhe3rkc1njzdpk4durg
REVERB_HOST="localhost"
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

## Menjalankan Aplikasi

1. Jalankan server Laravel:
   ```sh
   php artisan serve
   ```
2. Jalankan pemantau aset Vite (opsional saat pengembangan):
   ```sh
   npm run dev
   ```

Aplikasi sekarang dapat diakses melalui `http://localhost:8000`.
