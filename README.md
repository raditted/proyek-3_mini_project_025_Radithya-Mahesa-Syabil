# Pelaporan dan Pemantauan Keamanan Makanan MBG

Mini Project Proyek 3, D3 Teknik Informatika

## Identitas Mahasiswa

| Field | Isi                    |
| ----- | ---------------------- |
| Nama  | Radithya Mahesa Syabil |
| NIM   | 251511025              |
| Kelas | 2A D3                  |

## Kebutuhan Environment

- PHP >= 8.2
- Composer >= 2.x
- Node.js >= 18.x
- MySQL / MariaDB 8.x
- XAMPP / Laragon / Docker & Docker Compose

## Langkah Instalasi

### 1. Clone repository

```bash
git clone <URL_REPO> && cd Mini_Proyek_MBG
```

### 2. Install dependencies

```bash
composer install
npm install
```

### 3. Copy file environment

```bash
cp .env.example .env
```

### 4. Generate app key

```bash
php artisan key:generate
```

### 5. Jalankan MySQL dan phpMyAdmin

- **Jika Menggunakan Docker**
    ```bash
    docker compose up -d
    ```
    Konfigurasi `.env`:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=mbg_db
    DB_USERNAME=mbg_user
    DB_PASSWORD=mbg_password
    ```
    (phpMyAdmin otomatis tersedia di `http://localhost:8080`)
- **Jika Menggunaakan XAMPP / Laragon / MySQL Lokal**
    - Buka XAMPP/Laragon, nyalakan service **MySQL**.
    - Buat database baru bernama `mbg_db`.
    - Konfigurasi `.env`:
        ```env
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=mbg_db
        DB_USERNAME=root
        DB_PASSWORD=
        ```

### 6. Jalankan migration dan seeder

```bash
php artisan migrate:fresh --seed
```

### 7. Jalankan aplikasi

```bash
php artisan serve
npm run dev

```

Buka `http://localhost:8000` di browser.

## Akun Demo

| Role        | Email                 | Password |
| ----------- | --------------------- | -------- |
| Koordinator | koordinator@gmail.com | password |
| Pemantau    | pemantau@gmail.com    | password |
| Pelapor 1   | suki@gmail.com        | password |
| Pelapor 2   | panikga@gmail.com     | password |

## Tag Versi Peer Testing

```bash
git tag v1.0-peer-test
git push origin main --tags
```
