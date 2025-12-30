# TODO Plan: Ubah Label "Publikasi" menjadi "Jurnal"

## Task Analysis
User ingin mengubah semua label yang menggunakan kata "Publikasi" menjadi "Jurnal" pada file-file yang disebutkan.

## Files yang akan diedit:
1. `SIPRODO/resources/views/publikasi/create.blade.php`
2. `SIPRODO/resources/views/publikasi/edit.blade.php`
3. `SIPRODO/resources/views/publikasi/index.blade.php`
4. `SIPRODO/resources/views/publikasi/show.blade.php`

## Perubahan yang akan dilakukan:

### 1.publikasi/create.blade.php
- Header title: "Publikasi" → "Jurnal"
- Page title: "Formulir Data Publikasi" → "Formulir Data Jurnal"
- Form labels:
  - "Judul Publikasi" → "Judul Jurnal"
  - "File Publikasi" → "File Jurnal"
- Button text: "Simpan Publikasi" → "Simpan Jurnal"
- Description text: "informasi publikasi" → "informasi jurnal"

### 2. publikasi/edit.blade.php
- Header title: "Edit Data Publikasi" → "Edit Data Jurnal"
- Page title: "Formulir Edit Data Publikasi" → "Formulir Edit Data Jurnal"
- Form labels:
  - "Judul Publikasi" → "Judul Jurnal"
  - "File Publikasi" → "File Jurnal"
- Button text: "Update Publikasi" → "Update Jurnal"
- Description text: "informasi publikasi" → "informasi jurnal"

### 3. publikasi/index.blade.php
- Page title: "Data Publikasi" → "Data Jurnal"
- Navigation links: "publikasi" → "jurnal"
- Button texts: "Tambah Publikasi" → "Tambah Jurnal"

### 4. publikasi/show.blade.php
- Page title: "Detail Publikasi" → "Detail Jurnal"
- Headers dan labels yang berkaitan dengan publikasi

## Status: ✅ COMPLETED

### Implementation Progress:
- [x] **Update File: `SIPRODO/resources/views/publikasi/create.blade.php`** ✅
  - Header title: "Tambah Data Publikasi Baru" → "Tambah Data Jurnal Baru"
  - Page title: "Formulir Data Publikasi" → "Formulir Data Jurnal"
  - Description: "informasi publikasi" → "informasi jurnal"
  - Form labels: "Judul Publikasi" → "Judul Jurnal"
  - Placeholder: "Masukkan judul publikasi" → "Masukkan judul jurnal"
  - Section title: "Detail Publikasi" → "Detail Jurnal"
  - File label: "File Publikasi" → "File Jurnal"
  - Button text: "Simpan Publikasi" → "Simpan Jurnal"

- [x] **Update File: `SIPRODO/resources/views/publikasi/edit.blade.php`** ✅
  - Header title: "Edit Data Publikasi" → "Edit Data Jurnal"
  - Page title: "Formulir Edit Data Publikasi" → "Formulir Edit Data Jurnal"
  - Description: "informasi publikasi" → "informasi jurnal"
  - Form labels: "Judul Publikasi" → "Judul Jurnal"
  - Placeholder: "Masukkan judul publikasi" → "Masukkan judul jurnal"
  - Section title: "Detail Publikasi" → "Detail Jurnal"
  - File label: "File Publikasi" → "File Jurnal"
  - Button text: "Update Publikasi" → "Update Jurnal"

- [x] **Update File: `SIPRODO/resources/views/publikasi/index.blade.php`** ✅
  - Page title: "Data Publikasi" → "Data Jurnal"
  - Button text: "Tambah Publikasi" → "Tambah Jurnal"
  - Description: "publikasi ilmiah" → "jurnal ilmiah"

- [x] **Update File: `SIPRODO/resources/views/publikasi/show.blade.php`** ✅
  - Page title: "Detail Publikasi" → "Detail Jurnal"
  - Field label: "Tanggal Publikasi" → "Tanggal Jurnal"

### Technical Changes Applied:
1. **Consistent Labeling**: Semua label "Publikasi" diganti menjadi "Jurnal"
2. **Header Updates**: Page titles dan section headers
3. **Form Labels**: Input labels dan placeholders
4. **Button Text**: Action buttons
5. **Descriptions**: Helper text dan descriptions

### Files Updated:
- ✅ `SIPRODO/resources/views/publikasi/create.blade.php`
- ✅ `SIPRODO/resources/views/publikasi/edit.blade.php`
- ✅ `SIPRODO/resources/views/publikasi/index.blade.php`
- ✅ `SIPRODO/resources/views/publikasi/show.blade.php`

**Started**: 2025-01-28
**Completed**: 2025-01-28

## Result: ✅ SUCCESS - Semua label "Publikasi" telah berhasil diganti menjadi "Jurnal" pada semua file yang disebutkan!

## Technical Approach:
- Gunakan search_files untuk memastikan tidak ada yang terlewat
- Edit file satu per satu dengan hati-hati
- Pastikan konsistensi dalam semua perubahan
- Test setelah implementasi selesai
