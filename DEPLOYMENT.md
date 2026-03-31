# Panduan Deploy Laravel ke cPanel

## Persiapan Sebelum Deploy

### 1. Install Dependencies
```bash
composer install --optimize-autoloader --no-dev
npm run production
```

### 2. Generate Application Key
```bash
php artisan key:generate
```

### 3. Optimize untuk Production
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Upload ke cPanel

### 1. Upload Semua File
- Upload semua file ke root directory cPanel (public_html atau subdomain)
- Pastikan file `.htaccess` dan `index.php` di root sudah terupload

### 2. Set Permission
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

### 3. Konfigurasi Database
- Buat database di cPanel
- Update file `.env` dengan konfigurasi database cPanel:
```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=username_database
DB_PASSWORD=password_database
```

### 4. Jalankan Migration
```bash
php artisan migrate --force
```

### 5. Buat Storage Link
```bash
php artisan storage:link
```

## Struktur File untuk cPanel

```
public_html/
├── index.php (redirect ke public)
├── .htaccess (root htaccess)
├── public/
│   ├── index.php (Laravel entry point)
│   ├── .htaccess (Laravel htaccess)
│   ├── assets/
│   └── storage/ (symlink ke storage/app/public)
├── app/
├── bootstrap/
├── config/
├── database/
├── resources/
├── routes/
├── storage/
└── vendor/
```

## Troubleshooting

### Jika 500 Error:
1. Cek permission folder storage dan bootstrap/cache
2. Cek file .env sudah benar
3. Cek error log di cPanel

### Jika Route tidak berfungsi:
1. Pastikan mod_rewrite aktif di cPanel
2. Cek file .htaccess sudah benar
3. Cek file index.php di root sudah benar

## Optimasi Production

### 1. Set Environment
```env
APP_ENV=production
APP_DEBUG=false
```

### 2. Cache Configuration
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. Optimize Autoloader
```bash
composer dump-autoload --optimize
```
