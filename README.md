# Print App - Installation Guide

Proyek ini adalah aplikasi berbasis Laravel. Ikuti langkah-langkah di bawah ini untuk menjalankan proyek di mesin lokal Anda.

## Prasyarat

Pastikan Anda sudah menginstal:
- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL/MariaDB (atau Laragon)
- **Ngrok CLI** (Download di [ngrok.com](https://ngrok.com/download/windows?tab=install))

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

### 4. Konfigurasi Ngrok & URL (Wajib untuk Tim)
Agar fitur scan QR Code berfungsi di HP masing-masing, setiap anggota tim **wajib** memiliki akun Ngrok sendiri.

1. Daftar di [ngrok.com](https://dashboard.ngrok.com/signup) (Gratis).
2. Di dashboard Ngrok, klaim **Static Domain** gratis Anda (biasanya di menu *Universal Gateway > Domains*).
3. Jalankan perintah ini di terminal untuk login (token ada di dashboard akun ngrok anda, di menu *Getting Started > Your Authtoken*, perintah ini cukup dilakukan sekali):
   ```sh
   ngrok config add-authtoken TOKEN_ANDA_DISINI
   ```
4. Buka file `.env` dan isi `APP_URL` serta konfigurasi `REVERB_HOST` dengan domain static Anda.

**Contoh isi file `.env` jika domain Ngrok Anda adalah `kucing-terbang.ngrok-free.app`:**

```dotenv
# Ganti dengan domain Anda sendiri
APP_URL="https://kucing-terbang.ngrok-free.app"

# Konfigurasi Reverb (REVERB_HOST Wajib Sama dengan APP_URL tapi tanpa https://)
REVERB_APP_ID=821407
REVERB_APP_KEY=hieghuun6jbnpmgzqlhr
REVERB_APP_SECRET=9rhe3rkc1njzdpk4durg
REVERB_HOST="kucing-terbang.ngrok-free.app"
REVERB_PORT=8081
REVERB_SCHEME=https

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

> **Catatan:** Jika belum dapat domain static ngroknya, cukup isi `APP_URL="http://localhost"` atau `APP_URL="http://127.0.0.1:8000"` di `.env`.

### 5. Generate Application Key
```sh
php artisan key:generate
```

### 6. Hubungkan Storage ke Public
Jalankan storage link agar file yang tersimpan di storage bisa diakses:
```sh
php artisan storage:link
```

### 7. Migrasi Database
Pastikan database sudah dibuat di MySQL, lalu jalankan:
```sh
php artisan migrate
```

### 8. Seeding Database
Data dummy sudah tersedia di databaseseeder, jadi anda bisa seeding data untuk lihat datanya:
Copy folder files di folder public ke folder storage/app/public

```sh
php artisan db:seed
```

### 9. Instal Dependensi Frontend
```sh
npm install
npm run build
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
3. Atau jalankan perintah berikut jika ingin menjalankan reverb, artisan server, npm, dan ngrok bersamaan tanpa buat terminal baru:
   ```sh
   composer run dev
   ```
4. Atau jika hanya ingin jalankan artisan server dan npm saja:
   ```sh
   composer run laindev
   ```