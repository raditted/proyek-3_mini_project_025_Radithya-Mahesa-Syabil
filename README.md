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
git clone https://github.com/raditted/proyek-3_mini_project_025_Radithya-Mahesa-Syabil.git

cd proyek-3_mini_project_025_Radithya-Mahesa-Syabil
```

### 2. Install dependencies

```bash
composer install
npm install
```

### 3. Copy file environment

- **Linux / macOS / Git Bash:**
    ```bash
    cp .env.example .env
    ```
- **Windows (Command Prompt / PowerShell):**
    ```cmd
    copy .env.example .env
    ```

### 4. Generate app key

```bash
php artisan key:generate
```

### 5. Jalankan MySQL dan phpMyAdmin

- **Opsi A: Jika Menggunakan Docker**

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

- **Opsi B: Jika Menggunakan XAMPP / Laragon / MySQL Lokal Windows**
    1. Buka XAMPP atau Laragon, nyalakan service **MySQL**.
    2. Buat database baru bernama `mbg_db` (bisa lewat phpMyAdmin bawaan di `http://localhost/phpmyadmin` atau Laragon database manager).
    3. Konfigurasi `.env`:
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

Jalankan kedua perintah berikut pada **dua terminal terpisah**:

- **Terminal 1 (Server Laravel):**
    ```bash
    php artisan serve
    ```
- **Terminal 2 (Asset Bundler):**
    ```bash
    npm run dev
    ```
    _(Atau jika tidak ingin membuka terminal kedua, cukup jalankan `npm run build` sekali)._

Buka `http://localhost:8000` di browser.

## Akun Demo

| Role        | Email                 | Password |
| ----------- | --------------------- | -------- |
| Koordinator | koordinator@gmail.com | password |
| Pemantau    | pemantau@gmail.com    | password |
| Pelapor 1   | suki@gmail.com        | password |
| Pelapor 2   | panikga@gmail.com     | password |

## Versi Peer Testing

Aplikasi ini disiapkan untuk sesi pengujian pada versi **`v1.0-peer-test`**.

Jika Anda mengkloning repositori ini dan ingin memastikan berada di versi pengujian:

```bash
git checkout v1.0-peer-test
```
