# 📌 Sistem Manajemen Absensi

Aplikasi **Sistem Manajemen Absensi** berbasis web yang dibangun menggunakan framework **Laravel** untuk membantu pengelolaan data kehadiran pegawai secara digital.

Aplikasi ini memungkinkan admin dan pengguna untuk melakukan pencatatan absensi dengan sistem yang lebih terstruktur dan efisien.

# 🛠 Tech Stack

* Laravel
* PHP
* MySQL
* XAMPP
* HTML, CSS, JavaScript

# ⚙️ Persyaratan Sistem

Sebelum menjalankan aplikasi, pastikan sistem sudah memiliki:

* XAMPP (Apache & MySQL)
* Composer
* PHP
* Web Browser (Chrome, Edge, atau Firefox)

# 📥 Instalasi Project

## 1️⃣ Install XAMPP

1. Download XAMPP dari website resmi Apache Friends.
2. Jalankan installer XAMPP.
3. Pilih komponen berikut:

   * Apache
   * MySQL
   * PHP
4. Tentukan lokasi instalasi (default biasanya `C:\xampp`).
5. Setelah selesai, buka **XAMPP Control Panel**.
6. Jalankan **Apache** dan **MySQL**.

## 2️⃣ Setup Project

1. Extract file project aplikasi.
2. Copy folder project ke direktori berikut:

```
C:\xampp\htdocs
```

Contoh:

```
C:\xampp\htdocs\sistem-manajemen-absensi
```

## 3️⃣ Membuat Database

1. Buka browser lalu akses:

```
http://localhost/phpmyadmin
```

2. Klik **New / Baru** untuk membuat database baru.
3. Buat database dengan nama:

```
sistem-manajemen-absensi
```

## 4️⃣ Import Database

1. Pilih database yang sudah dibuat.
2. Klik menu **Import**.
3. Pilih file database yang berada di folder project:

```
database.sql
```

4. Klik **Go / Kirim** untuk memulai proses import.

Jika berhasil, tabel database akan otomatis muncul.

## 5️⃣ Konfigurasi File Environment

Buka file `.env` pada folder project lalu sesuaikan konfigurasi database berikut:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sistem-manajemen-absensi
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan nilai `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` dengan konfigurasi MySQL yang digunakan.

## 6️⃣ Jalankan Perintah Laravel

Buka terminal atau command prompt dan pastikan berada pada folder project di dalam `htdocs`.

Contoh:

```
C:\xampp\htdocs\sistem-manajemen-absensi
```

Kemudian jalankan perintah berikut:

```
php artisan optimize
php artisan config:cache
php artisan route:clear
php artisan storage:link
```

Jika database belum diimport, jalankan juga:

```
php artisan migrate:fresh --seed
```

## 7️⃣ Menjalankan Aplikasi

Pastikan **Apache** dan **MySQL** pada XAMPP sudah berjalan.

Kemudian jalankan server Laravel:

```
php artisan serve
```

Aplikasi dapat diakses melalui browser pada alamat:

```
http://127.0.0.1:8000
```

# 👤 Akun Login Default

### Admin

```
Username: admin
Password: admin123
```

### User

```
Username: user
Password: user123
```
