# TODO: Perubahan "SuperAdmin" → "Admin" - UPDATED

## File Code yang Perlu Diubah:

### 1. SIPRODO/database/migrations/2025_01_01_000001_add_role_to_users_table.php
- [ ] Ubah enum dari `['super_admin', 'kaprodi', 'dosen']` → `['admin', 'kaprodi', 'dosen']`

### 2. SIPRODO/routes/web.php
- [ ] Ubah semua middleware `role:super_admin,kaprodi` → `role:admin,kaprodi`

## Database MySQL (via Command):
- [ ] Jalankan UPDATE query untuk mengubah nilai di tabel users
- [ ] UPDATE role di tabel users SET role = 'admin' WHERE role = 'super_admin'

## File SQL Dump (jika ada):
- [ ] siproda.sql: Update role_types dan users table jika file ada

## Tahap Selanjutnya:
- [ ] Hapus cache views (php artisan view:clear)
- [ ] Test aplikasi untuk memastikan tidak ada konflik
