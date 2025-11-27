# 📘 PANDUAN INSTALASI siprodo
## Sistem Informasi Produktivitas Dosen - Telkom University Jakarta

---

## 📋 Daftar Isi

1. [Persyaratan Sistem](#persyaratan-sistem)
2. [Instalasi di Windows](#instalasi-di-windows)
3. [Instalasi di macOS](#instalasi-di-macos)
4. [Instalasi di Linux](#instalasi-di-linux)
5. [Konfigurasi Database](#konfigurasi-database)
6. [Troubleshooting](#troubleshooting)

---

## 1. PERSYARATAN SISTEM

### Minimum Requirements

| Komponen | Spesifikasi Minimum |
|----------|---------------------|
| Processor | Intel Core i3 / AMD Ryzen 3 |
| RAM | 4 GB |
| Storage | 2 GB free space |
| OS | Windows 10, macOS 10.15, Ubuntu 20.04 |

### Software Requirements

| Software | Version | Download Link |
|----------|---------|---------------|
| PHP | 8.2 atau lebih tinggi | [php.net](https://www.php.net/downloads) |
| Composer | 2.0 atau lebih tinggi | [getcomposer.org](https://getcomposer.org/download/) |
| Node.js | 18.x atau lebih tinggi | [nodejs.org](https://nodejs.org/) |
| MySQL | 8.0 atau lebih tinggi | [mysql.com](https://dev.mysql.com/downloads/) |
| Git | Latest | [git-scm.com](https://git-scm.com/downloads) |

---

## 2. INSTALASI DI WINDOWS

### Step 1: Install PHP

1. Download PHP dari [windows.php.net](https://windows.php.net/download/)
2. Extract ke `C:\php`
3. Tambahkan `C:\php` ke System PATH
4. Copy `php.ini-development` menjadi `php.ini`
5. Edit `php.ini` dan aktifkan extensions:
   ```ini
   extension=pdo_mysql
   extension=mbstring
   extension=openssl
   extension=fileinfo
   extension=gd
   extension=zip
   ```

### Step 2: Install Composer

1. Download Composer installer dari [getcomposer.org](https://getcomposer.org/Composer-Setup.exe)
2. Jalankan installer
3. Pilih PHP executable (`C:\php\php.exe`)
4. Selesaikan instalasi

### Step 3: Install Node.js

1. Download Node.js installer dari [nodejs.org](https://nodejs.org/)
2. Jalankan installer
3. Ikuti wizard instalasi
4. Verify instalasi:
   ```cmd
   node --version
   npm --version
   ```

### Step 4: Install MySQL

1. Download MySQL Installer dari [mysql.com](https://dev.mysql.com/downloads/installer/)
2. Pilih "Developer Default"
3. Set root password
4. Selesaikan instalasi

### Step 5: Clone & Setup Project

```cmd
# Clone repository
git clone https://github.com/your-username/siprodo.git
cd siprodo\siprodo

# Install dependencies
composer install
npm install

# Setup environment
copy .env.example .env
php artisan key:generate

# Create database
mysql -u root -p
CREATE DATABASE siprodo;
exit;

# Run migrations
php artisan migrate --seed

# Create storage link
php artisan storage:link

# Build assets
npm run build

# Start server
php artisan serve
```

---

## 3. INSTALASI DI macOS

### Step 1: Install Homebrew

```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

### Step 2: Install PHP

```bash
brew install php@8.2
brew link php@8.2
php --version
```

### Step 3: Install Composer

```bash
brew install composer
composer --version
```

### Step 4: Install Node.js

```bash
brew install node@18
node --version
npm --version
```

### Step 5: Install MySQL

```bash
brew install mysql
brew services start mysql
mysql_secure_installation
```

### Step 6: Clone & Setup Project

```bash
# Clone repository
git clone https://github.com/your-username/siprodo.git
cd siprodo/siprodo

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Create database
mysql -u root -p
CREATE DATABASE siprodo;
exit;

# Run migrations
php artisan migrate --seed

# Create storage link
php artisan storage:link

# Build assets
npm run build

# Start server
php artisan serve
```

---

## 4. INSTALASI DI LINUX (Ubuntu/Debian)

### Step 1: Update System

```bash
sudo apt update
sudo apt upgrade -y
```

### Step 2: Install PHP

```bash
sudo apt install -y php8.2 php8.2-cli php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip php8.2-gd
php --version
```

### Step 3: Install Composer

```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
composer --version
```

### Step 4: Install Node.js

```bash
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs
node --version
npm --version
```

### Step 5: Install MySQL

```bash
sudo apt install -y mysql-server
sudo mysql_secure_installation
```

### Step 6: Clone & Setup Project

```bash
# Clone repository
git clone https://github.com/your-username/siprodo.git
cd siprodo/siprodo

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Create database
sudo mysql -u root -p
CREATE DATABASE siprodo;
exit;

# Run migrations
php artisan migrate --seed

# Create storage link
php artisan storage:link

# Build assets
npm run build

# Start server
php artisan serve
```

---

## 5. KONFIGURASI DATABASE

### Menggunakan MySQL

Edit file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=siprodo
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Menggunakan SQLite (Untuk Development)

Edit file `.env`:

```env
DB_CONNECTION=sqlite
# DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD tidak diperlukan
```

Buat file database:

```bash
touch database/database.sqlite
```

### Menggunakan PostgreSQL

Edit file `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=siprodo
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

---

## 6. TROUBLESHOOTING

### Error: "Class 'PDO' not found"

**Solusi:**
```bash
# Windows
# Edit php.ini dan uncomment:
extension=pdo_mysql

# Linux/macOS
sudo apt install php8.2-mysql
# atau
brew install php@8.2
```

### Error: "Permission denied" saat storage:link

**Solusi:**
```bash
# Linux/macOS
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R $USER:www-data storage bootstrap/cache

# Windows (Run as Administrator)
icacls storage /grant Users:F /T
icacls bootstrap\cache /grant Users:F /T
```

### Error: "SQLSTATE[HY000] [2002] Connection refused"

**Solusi:**
```bash
# Pastikan MySQL berjalan
# Windows
net start MySQL80

# Linux
sudo systemctl start mysql

# macOS
brew services start mysql
```

### Error: "npm ERR! code ELIFECYCLE"

**Solusi:**
```bash
# Hapus node_modules dan package-lock.json
rm -rf node_modules package-lock.json

# Install ulang
npm install
```

### Error: "Vite manifest not found"

**Solusi:**
```bash
# Build assets
npm run build

# Atau untuk development
npm run dev
```

### Error: "The stream or file could not be opened"

**Solusi:**
```bash
# Buat folder logs jika belum ada
mkdir -p storage/logs

# Set permission
chmod -R 775 storage
```

### Port 8000 sudah digunakan

**Solusi:**
```bash
# Gunakan port lain
php artisan serve --port=8080
```

### Composer install sangat lambat

**Solusi:**
```bash
# Gunakan mirror Indonesia
composer config -g repos.packagist composer https://packagist.jp
```

---

## 📞 Bantuan Lebih Lanjut

Jika masih mengalami masalah:

1. Cek dokumentasi Laravel: [laravel.com/docs](https://laravel.com/docs)
2. Cek log error di `storage/logs/laravel.log`
3. Hubungi tim support: support@telkomuniversity.ac.id

---

## ✅ Checklist Instalasi

- [ ] PHP 8.2+ terinstall
- [ ] Composer terinstall
- [ ] Node.js 18+ terinstall
- [ ] MySQL/SQLite terinstall
- [ ] Repository di-clone
- [ ] Dependencies terinstall (`composer install` & `npm install`)
- [ ] File `.env` dikonfigurasi
- [ ] Database dibuat
- [ ] Migrations dijalankan (`php artisan migrate --seed`)
- [ ] Storage link dibuat (`php artisan storage:link`)
- [ ] Assets di-build (`npm run build`)
- [ ] Server berjalan (`php artisan serve`)
- [ ] Bisa login dengan user default

---

**Selamat! siprodo sudah siap digunakan! 🎉**

Akses aplikasi di: `http://localhost:8000`

Login dengan:
- Email: `admin@telkomuniversity.ac.id`
- Password: `password`

