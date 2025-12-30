# TODO: Perubahan "SuperAdmin" → "Admin"

## File Code yang Perlu Diubah:

### 1. SIPRODO/app/Models/User.php
- [ ] `ROLE_SUPER = 'super_admin'` → `ROLE_ADMIN = 'admin'`
- [ ] `isSuperAdmin()` → `isAdmin()`

### 2. SIPRODO/app/Http/Controllers/DashboardController.php
- [ ] `$isSuperAdmin` → `$isAdmin`
- [ ] `$user->isSuperAdmin()` → `$user->isAdmin()`

### 3. SIPRODO/resources/views/dashboard.blade.php
- [ ] Semua `isSuperAdmin()` → `isAdmin()`
- [ ] Semua `$isSuperAdmin` → `$isAdmin`

### 4. SIPRODO/database/seeders/UserSeeder.php
- [ ] `'role' => 'super_admin'` → `'role' => 'admin'`
- [ ] `'name' => 'Super Admin'` → `'name' => 'Admin'`

## Database SQL yang Perlu Diubah:

### 5. siproda.sql
- [ ] `('super_admin', 'Super Admin')` → `('admin', 'Admin')` di role_types
- [ ] `('super_admin', ...)` → `('admin', ...)` di users

## Database MySQL (via Command):
- [ ] Jalankan UPDATE query untuk mengubah nilai di tabel users
- [ ] UPDATE role di tabel users SET role = 'admin' WHERE role = 'super_admin'

## Tahap Selanjutnya:
- [ ] Hapus cache views (php artisan view:clear)
- [ ] Test aplikasi untuk memastikan tidak ada konflik

