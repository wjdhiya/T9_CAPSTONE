# DOKUMENTASI CAPSTONE DESIGN PROJECT
## Sistem Informasi Inventaris Penelitian, Publikasi, dan Pengabdian Masyarakat (siprodo)
### Telkom University Jakarta

---

## 📋 DAFTAR ISI

1. [Pendahuluan](#pendahuluan)
2. [Analisis Kebutuhan](#analisis-kebutuhan)
3. [Perancangan Sistem](#perancangan-sistem)
4. [Implementasi](#implementasi)
5. [Tools dan Teknologi](#tools-dan-teknologi)
6. [Metode Prototype](#metode-prototype)
7. [Testing](#testing)
8. [Kesimpulan dan Saran](#kesimpulan-dan-saran)

---

## 1. PENDAHULUAN

### 1.1 Latar Belakang

Berdasarkan hasil wawancara dengan Ibu Kaprodi Sistem Informasi Telkom University Jakarta, ditemukan beberapa permasalahan kritis dalam pengelolaan data penelitian, publikasi, dan pengabdian masyarakat:

1. **Tidak ada alur kerja baku** - Pengumpulan data dilakukan secara ad-hoc tanpa standar operasional prosedur
2. **Pengelolaan manual** - Semua data dikelola menggunakan Excel yang tersebar di berbagai file
3. **Sistem terisolasi** - Sistem pengmas yang ada hanya bisa diakses oleh masing-masing dosen
4. **Rekapitulasi lambat** - Proses rekapitulasi memakan waktu berhari-hari bahkan berminggu-minggu
5. **Tidak ada monitoring** - Tidak ada sistem untuk memonitor produktivitas dosen secara real-time

### 1.2 Rumusan Masalah

1. Bagaimana merancang dan mengembangkan sistem informasi berbasis web yang dapat mengintegrasikan data penelitian, publikasi, dan pengabdian masyarakat dosen di Telkom University Jakarta?

2. Bagaimana sistem tersebut dapat membantu mempercepat proses rekapitulasi dan penyusunan laporan rutin yang sebelumnya dilakukan secara manual?

3. Bagaimana sistem dapat menyediakan fitur monitoring untuk mendukung evaluasi produktivitas dosen secara lebih akurat dan real-time?

### 1.3 Tujuan Perancangan

1. Mengembangkan sistem informasi berbasis web yang terintegrasi untuk pengelolaan data penelitian, publikasi, dan pengabdian masyarakat dosen Telkom University Jakarta

2. Menyediakan mekanisme rekapitulasi data secara otomatis untuk mendukung kebutuhan pelaporan rutin maupun akreditasi

3. Merancang fitur monitoring dan dashboard produktivitas dosen yang dapat memberikan informasi akurat terkait capaian penelitian, publikasi, dan pengabdian masyarakat

4. Meminimalisasi risiko kesalahan pencatatan dan meningkatkan efisiensi waktu dalam pengelolaan data akademik

### 1.4 Manfaat Perancangan

**Bagi Program Studi:**
- Memudahkan Kaprodi dan admin dalam mengelola, memverifikasi, dan melaporkan data
- Mempercepat proses akreditasi dengan data yang terorganisir

**Bagi Dosen:**
- Kemudahan dalam menyimpan dan memperbarui aktivitas penelitian
- Monitoring mandiri terhadap produktivitas

**Bagi Institusi:**
- Data akademik yang akurat dan up-to-date
- Mendukung pengambilan keputusan berbasis data

---

## 2. ANALISIS KEBUTUHAN

### 2.1 Analisis Akar Permasalahan dan Solusi

| No | Akar Permasalahan | Potensi Solusi |
|----|-------------------|----------------|
| 1 | Tidak ada alur kerja baku dalam pengumpulan data | Perancangan alur kerja standar (SOP) pengelolaan data penelitian, publikasi, dan pengmas |
| 2 | Pengelolaan data masih manual menggunakan Excel | Pengembangan sistem informasi berbasis web yang dapat mengelola data secara terpusat |
| 3 | Sistem pengmas yang ada hanya bersifat individual | Integrasi sistem pengmas agar dapat diakses oleh dosen, admin, dan Kaprodi dengan peran berbeda |
| 4 | Rekapitulasi data membutuhkan waktu lama | Perancangan fitur rekapitulasi otomatis untuk laporan periodik (semesteran/tahunan) |
| 5 | Tidak adanya monitoring produktivitas dosen | Pengembangan dashboard analitik yang dapat menampilkan produktivitas dosen secara visual dan real-time |

### 2.2 Functional Requirements

#### FR-01: Manajemen User
- FR-01.1: Sistem dapat melakukan autentikasi user (login/logout)
- FR-01.2: Sistem dapat mengelola 3 role: Super Admin, Kaprodi, dan Dosen
- FR-01.3: Sistem dapat mengelola profil user (NIDN, NIP, kontak, dll)

#### FR-02: Manajemen Penelitian
- FR-02.1: Dosen dapat menambah, mengubah, dan menghapus data penelitian
- FR-02.2: Sistem dapat menyimpan informasi lengkap penelitian (judul, abstrak, jenis, dana, periode, anggota, mahasiswa terlibat)
- FR-02.3: Sistem dapat upload file proposal dan laporan penelitian
- FR-02.4: Kaprodi/Admin dapat memverifikasi data penelitian
- FR-02.5: Sistem dapat menampilkan status penelitian (proposal, berjalan, selesai, ditolak)

#### FR-03: Manajemen Publikasi
- FR-03.1: Dosen dapat menambah, mengubah, dan menghapus data publikasi
- FR-03.2: Sistem dapat menyimpan informasi publikasi (jurnal, prosiding, buku, paten, HKI)
- FR-03.3: Sistem dapat mencatat indexing (Scopus, WoS, SINTA) dan quartile
- FR-03.4: Sistem dapat mengaitkan publikasi dengan penelitian
- FR-03.5: Kaprodi/Admin dapat memverifikasi data publikasi

#### FR-04: Manajemen Pengabdian Masyarakat
- FR-04.1: Dosen dapat menambah, mengubah, dan menghapus data pengmas
- FR-04.2: Sistem dapat menyimpan informasi pengmas (lokasi, mitra, peserta, dokumentasi)
- FR-04.3: Sistem dapat upload file proposal, laporan, dan dokumentasi
- FR-04.4: Kaprodi/Admin dapat memverifikasi data pengmas

#### FR-05: Dashboard dan Monitoring
- FR-05.1: Sistem dapat menampilkan dashboard produktivitas dosen
- FR-05.2: Sistem dapat menampilkan statistik penelitian, publikasi, dan pengmas
- FR-05.3: Sistem dapat menampilkan grafik trend per tahun/semester
- FR-05.4: Sistem dapat menampilkan rasio produktivitas (penelitian/dosen, publikasi/dosen, pengmas/dosen)

#### FR-06: Pelaporan dan Export
- FR-06.1: Sistem dapat generate laporan dalam format Excel
- FR-06.2: Sistem dapat generate laporan dalam format PDF
- FR-06.3: Sistem dapat filter laporan berdasarkan tahun, semester, jenis, status
- FR-06.4: Sistem dapat export data untuk kebutuhan akreditasi

### 2.3 Non-Functional Requirements

#### NFR-01: Usability
- Interface user-friendly dengan desain modern
- Responsive design untuk akses via desktop dan mobile
- Navigasi intuitif dengan maksimal 3 klik untuk mencapai fitur utama

#### NFR-02: Performance
- Waktu loading halaman maksimal 3 detik
- Sistem dapat menangani minimal 100 concurrent users
- Database query optimization untuk laporan besar

#### NFR-03: Security
- Autentikasi menggunakan email dan password terenkripsi
- Role-based access control (RBAC)
- Session management dengan timeout
- Input validation dan sanitization

#### NFR-04: Reliability
- System uptime minimal 99%
- Backup database otomatis harian
- Error handling dan logging

#### NFR-05: Maintainability
- Kode terstruktur mengikuti best practices Laravel
- Dokumentasi kode yang lengkap
- Modular architecture untuk kemudahan pengembangan

---

## 3. PERANCANGAN SISTEM

### 3.1 Arsitektur Sistem

Sistem siprodo menggunakan arsitektur **MVC (Model-View-Controller)** dengan framework Laravel:

```
┌─────────────────────────────────────────────────────────┐
│                    PRESENTATION LAYER                    │
│  (Blade Templates + Tailwind CSS + Alpine.js/Livewire) │
└────────────────────┬────────────────────────────────────┘
                     │
┌────────────────────▼────────────────────────────────────┐
│                   APPLICATION LAYER                      │
│         (Controllers + Middleware + Requests)            │
└────────────────────┬────────────────────────────────────┘
                     │
┌────────────────────▼────────────────────────────────────┐
│                    BUSINESS LAYER                        │
│              (Models + Services + Policies)              │
└────────────────────┬────────────────────────────────────┘
                     │
┌────────────────────▼────────────────────────────────────┐
│                      DATA LAYER                          │
│              (Database + File Storage)                   │
└─────────────────────────────────────────────────────────┘
```

### 3.2 Database Schema

#### Tabel: users
| Field | Type | Description |
|-------|------|-------------|
| id | BIGINT | Primary Key |
| name | VARCHAR(255) | Nama lengkap |
| email | VARCHAR(255) | Email (unique) |
| password | VARCHAR(255) | Password (hashed) |
| role | ENUM | super_admin, kaprodi, dosen |
| nidn | VARCHAR(20) | NIDN dosen |
| nip | VARCHAR(20) | NIP dosen |
| phone | VARCHAR(20) | Nomor telepon |
| position | VARCHAR(100) | Jabatan |
| department | VARCHAR(100) | Program studi |
| is_active | BOOLEAN | Status aktif |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diupdate |

#### Tabel: penelitian
| Field | Type | Description |
|-------|------|-------------|
| id | BIGINT | Primary Key |
| user_id | BIGINT | Foreign Key ke users |
| judul | VARCHAR(500) | Judul penelitian |
| abstrak | TEXT | Abstrak penelitian |
| jenis | ENUM | internal, eksternal, mandiri |
| sumber_dana | VARCHAR(255) | Sumber pendanaan |
| dana | DECIMAL(15,2) | Jumlah dana |
| tahun | YEAR | Tahun pelaksanaan |
| semester | ENUM | ganjil, genap |
| tanggal_mulai | DATE | Tanggal mulai |
| tanggal_selesai | DATE | Tanggal selesai |
| status | ENUM | proposal, berjalan, selesai, ditolak |
| file_proposal | VARCHAR(255) | Path file proposal |
| file_laporan | VARCHAR(255) | Path file laporan |
| anggota | JSON | Array anggota tim |
| mahasiswa_terlibat | JSON | Array mahasiswa |
| catatan | TEXT | Catatan tambahan |
| status_verifikasi | ENUM | pending, verified, rejected |
| catatan_verifikasi | TEXT | Catatan verifikasi |
| verified_by | BIGINT | Foreign Key ke users |
| verified_at | TIMESTAMP | Waktu verifikasi |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diupdate |
| deleted_at | TIMESTAMP | Soft delete |

#### Tabel: publikasi
| Field | Type | Description |
|-------|------|-------------|
| id | BIGINT | Primary Key |
| user_id | BIGINT | Foreign Key ke users |
| penelitian_id | BIGINT | Foreign Key ke penelitian (nullable) |
| judul | VARCHAR(500) | Judul publikasi |
| abstrak | TEXT | Abstrak |
| jenis | ENUM | jurnal, prosiding, buku, book_chapter, paten, hki |
| nama_publikasi | VARCHAR(255) | Nama jurnal/prosiding/penerbit |
| penerbit | VARCHAR(255) | Penerbit |
| issn_isbn | VARCHAR(50) | ISSN/ISBN |
| volume | VARCHAR(20) | Volume |
| nomor | VARCHAR(20) | Nomor |
| halaman | VARCHAR(20) | Halaman |
| tahun | YEAR | Tahun terbit |
| semester | ENUM | ganjil, genap |
| tanggal_terbit | DATE | Tanggal terbit |
| quartile | ENUM | Q1, Q2, Q3, Q4, non-quartile |
| indexing | ENUM | scopus, wos, sinta1-6, non-indexed |
| doi | VARCHAR(255) | DOI |
| url | VARCHAR(500) | URL publikasi |
| file_publikasi | VARCHAR(255) | Path file |
| penulis | JSON | Array penulis |
| mahasiswa_terlibat | JSON | Array mahasiswa |
| catatan | TEXT | Catatan |
| status_verifikasi | ENUM | pending, verified, rejected |
| catatan_verifikasi | TEXT | Catatan verifikasi |
| verified_by | BIGINT | Foreign Key ke users |
| verified_at | TIMESTAMP | Waktu verifikasi |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diupdate |
| deleted_at | TIMESTAMP | Soft delete |

#### Tabel: pengabdian_masyarakat
| Field | Type | Description |
|-------|------|-------------|
| id | BIGINT | Primary Key |
| user_id | BIGINT | Foreign Key ke users |
| judul | VARCHAR(500) | Judul pengmas |
| deskripsi | TEXT | Deskripsi kegiatan |
| jenis | ENUM | internal, eksternal, mandiri |
| sumber_dana | VARCHAR(255) | Sumber pendanaan |
| dana | DECIMAL(15,2) | Jumlah dana |
| tahun | YEAR | Tahun pelaksanaan |
| semester | ENUM | ganjil, genap |
| tanggal_mulai | DATE | Tanggal mulai |
| tanggal_selesai | DATE | Tanggal selesai |
| lokasi | VARCHAR(255) | Lokasi kegiatan |
| mitra | VARCHAR(255) | Mitra kerjasama |
| jumlah_peserta | INT | Jumlah peserta |
| status | ENUM | proposal, berjalan, selesai, ditolak |
| file_proposal | VARCHAR(255) | Path file proposal |
| file_laporan | VARCHAR(255) | Path file laporan |
| file_dokumentasi | VARCHAR(255) | Path file dokumentasi |
| anggota | JSON | Array anggota tim |
| mahasiswa_terlibat | JSON | Array mahasiswa |
| catatan | TEXT | Catatan |
| status_verifikasi | ENUM | pending, verified, rejected |
| catatan_verifikasi | TEXT | Catatan verifikasi |
| verified_by | BIGINT | Foreign Key ke users |
| verified_at | TIMESTAMP | Waktu verifikasi |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diupdate |
| deleted_at | TIMESTAMP | Soft delete |

### 3.3 ERD (Entity Relationship Diagram)

```
┌─────────────────┐
│     USERS       │
├─────────────────┤
│ PK id           │
│    name         │
│    email        │
│    password     │
│    role         │
│    nidn         │
│    nip          │
│    ...          │
└────────┬────────┘
         │
         │ 1:N
         │
    ┌────┴────┬────────────┬────────────┐
    │         │            │            │
┌───▼──────┐ ┌▼──────────┐ ┌▼──────────┐
│PENELITIAN│ │ PUBLIKASI │ │ PENGMAS   │
├──────────┤ ├───────────┤ ├───────────┤
│PK id     │ │PK id      │ │PK id      │
│FK user_id│ │FK user_id │ │FK user_id │
│  judul   │ │FK penelit.│ │  judul    │
│  ...     │ │  judul    │ │  ...      │
└──────────┘ │  ...      │ └───────────┘
             └───────────┘
```

---

## 4. IMPLEMENTASI

### 4.1 Struktur Folder Project

```
siprodo/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   ├── DashboardController.php
│   │   │   ├── PenelitianController.php
│   │   │   ├── PublikasiController.php
│   │   │   └── PengabdianMasyarakatController.php
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Models/
│   │   ├── User.php
│   │   ├── Penelitian.php
│   │   ├── Publikasi.php
│   │   └── PengabdianMasyarakat.php
│   └── Policies/
├── database/
│   ├── migrations/
│   │   ├── 2025_01_01_000001_add_role_to_users_table.php
│   │   ├── 2025_01_01_000002_create_penelitian_table.php
│   │   ├── 2025_01_01_000003_create_publikasi_table.php
│   │   └── 2025_01_01_000004_create_pengabdian_masyarakat_table.php
│   └── seeders/
│       ├── UserSeeder.php
│       └── PenelitianSeeder.php
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   ├── auth/
│   │   ├── dashboard/
│   │   ├── penelitian/
│   │   ├── publikasi/
│   │   └── pengmas/
│   ├── css/
│   └── js/
├── routes/
│   └── web.php
└── public/
    └── storage/
```

### 4.2 Color Scheme Implementation

Sistem menggunakan color palette Telkom University:

- **Primary Red (#a02127)**: Header, buttons utama, highlights
- **Secondary Green (#10784b)**: Success states, verified badges
- **Neutral Gray (#818183)**: Text secondary, borders
- **Dark Gray (#585858)**: Text primary, headings
- **White (#FFFFFF)**: Background, cards

Implementasi di Tailwind Config:
```javascript
colors: {
  'telkom-red': '#a02127',
  'telkom-gray': '#818183',
  'telkom-dark': '#585858',
  'telkom-white': '#FFFFFF',
  'telkom-green': '#10784b',
}
```

---

## 5. TOOLS DAN TEKNOLOGI

### 5.1 Backend Technologies

| Tool/Framework | Version | Fungsi |
|----------------|---------|--------|
| PHP | 8.2+ | Server-side programming language |
| Laravel | 11.x | PHP Framework untuk MVC architecture |
| MySQL/SQLite | 8.0+ / Latest | Relational Database Management System |
| Composer | 2.x | PHP Dependency Manager |

### 5.2 Frontend Technologies

| Tool/Framework | Version | Fungsi |
|----------------|---------|--------|
| Tailwind CSS | 3.4+ | Utility-first CSS framework |
| Alpine.js / Livewire | Latest | JavaScript framework untuk interaktivitas |
| Blade | Laravel 11 | Template engine |
| Chart.js / ApexCharts | Latest | Library untuk visualisasi data |
| Vite | 6.x | Frontend build tool |

### 5.3 Development Tools

| Tool | Fungsi |
|------|--------|
| VS Code | Code editor |
| Git | Version control |
| Laravel Pint | Code formatter |
| PHPUnit | Testing framework |
| Laravel Debugbar | Debugging tool |
| Postman | API testing |

### 5.4 Additional Libraries

| Library | Fungsi |
|---------|--------|
| Laravel Excel | Export data ke Excel |
| DomPDF / Snappy | Generate PDF reports |
| Intervention Image | Image processing untuk upload |
| Laravel Sanctum | API authentication (jika diperlukan) |

---

## 6. METODE PROTOTYPE

### 6.1 Tahapan Metode Prototype

Pengembangan sistem siprodo menggunakan **Metode Prototype** dengan tahapan sebagai berikut:

#### Tahap 1: Pengumpulan Kebutuhan (Requirements Gathering)
**Aktivitas:**
- Wawancara dengan Ibu Kaprodi Sistem Informasi
- Identifikasi pain points dalam sistem yang ada
- Analisis kebutuhan fungsional dan non-fungsional
- Dokumentasi hasil wawancara

**Output:**
- Dokumen analisis kebutuhan
- Functional requirements
- Non-functional requirements

#### Tahap 2: Perancangan Prototype Awal (Quick Design)
**Aktivitas:**
- Desain database schema (ERD)
- Desain arsitektur sistem
- Wireframe dan mockup UI
- Perancangan alur kerja (workflow)

**Output:**
- ERD diagram
- Wireframe halaman utama
- Mockup dashboard
- Flowchart proses bisnis

#### Tahap 3: Pembangunan Prototype (Build Prototype)
**Aktivitas:**
- Setup environment Laravel
- Implementasi database migrations
- Pembuatan models dan relationships
- Implementasi CRUD dasar
- Desain UI dengan Tailwind CSS
- Implementasi fitur prioritas tinggi

**Output:**
- Prototype sistem yang dapat dijalankan
- Fitur login dan manajemen user
- CRUD penelitian, publikasi, pengmas
- Dashboard sederhana

#### Tahap 4: Evaluasi Prototype (Customer Evaluation)
**Aktivitas:**
- Demo prototype kepada stakeholder (Kaprodi)
- Pengumpulan feedback
- Identifikasi kekurangan dan perbaikan
- Validasi fitur dengan kebutuhan

**Output:**
- Dokumen feedback
- Daftar perbaikan dan enhancement
- Approval untuk iterasi berikutnya

#### Tahap 5: Perbaikan Prototype (Refine Prototype)
**Aktivitas:**
- Implementasi feedback dari evaluasi
- Penambahan fitur baru
- Perbaikan UI/UX
- Optimasi performa
- Testing dan debugging

**Output:**
- Prototype yang telah diperbaiki
- Fitur tambahan sesuai feedback
- Bug fixes

#### Tahap 6: Iterasi (Iteration)
Tahap 3-5 diulang hingga sistem memenuhi semua kebutuhan dan mendapat approval final dari stakeholder.

#### Tahap 7: Finalisasi (Product Engineering)
**Aktivitas:**
- Code refactoring
- Comprehensive testing
- Dokumentasi lengkap
- Deployment preparation
- User training

**Output:**
- Sistem final yang siap deploy
- Dokumentasi teknis dan user manual
- Test reports
- Deployment guide

### 6.2 Keunggulan Metode Prototype untuk siprodo

1. **Feedback Cepat**: Stakeholder dapat melihat dan mengevaluasi sistem sejak awal
2. **Fleksibilitas**: Mudah melakukan perubahan berdasarkan kebutuhan yang berkembang
3. **Mengurangi Risiko**: Kesalahan dapat dideteksi lebih awal
4. **User Involvement**: Pengguna terlibat aktif dalam pengembangan
5. **Time Efficient**: Pengembangan lebih cepat dengan fokus pada fitur prioritas

### 6.3 Timeline Pengembangan

| Tahap | Durasi | Minggu |
|-------|--------|--------|
| Requirements Gathering | 1 minggu | Minggu 1 |
| Quick Design | 1 minggu | Minggu 2 |
| Build Prototype (Iterasi 1) | 2 minggu | Minggu 3-4 |
| Customer Evaluation | 3 hari | Minggu 5 |
| Refine Prototype | 1 minggu | Minggu 5-6 |
| Build Prototype (Iterasi 2) | 2 minggu | Minggu 7-8 |
| Customer Evaluation | 3 hari | Minggu 9 |
| Refine & Finalize | 2 minggu | Minggu 9-10 |
| Testing & Documentation | 1 minggu | Minggu 11 |
| Deployment & Training | 1 minggu | Minggu 12 |

**Total: 12 Minggu (3 Bulan)**

---

## 7. TESTING

### 7.1 Unit Testing

Testing untuk setiap komponen/fungsi individual:

```php
// Example: PenelitianTest.php
public function test_dosen_can_create_penelitian()
{
    $user = User::factory()->create(['role' => 'dosen']);
    
    $response = $this->actingAs($user)->post('/penelitian', [
        'judul' => 'Test Penelitian',
        'jenis' => 'internal',
        'tahun' => 2024,
        'semester' => 'ganjil',
    ]);
    
    $response->assertStatus(302);
    $this->assertDatabaseHas('penelitian', [
        'judul' => 'Test Penelitian',
    ]);
}
```

### 7.2 Integration Testing

Testing untuk interaksi antar komponen:

- Test workflow lengkap dari input data hingga verifikasi
- Test relasi antar tabel (penelitian -> publikasi)
- Test file upload dan storage

### 7.3 User Acceptance Testing (UAT)

Testing dengan user sebenarnya (Kaprodi dan Dosen):

**Test Scenarios:**
1. Login sebagai Dosen dan input data penelitian
2. Login sebagai Kaprodi dan verifikasi data
3. Generate laporan semester
4. Export data ke Excel
5. View dashboard produktivitas

**Acceptance Criteria:**
- Semua fitur berfungsi sesuai requirement
- UI mudah digunakan
- Performa memadai
- Data akurat

---

## 8. KESIMPULAN DAN SARAN

### 8.1 Kesimpulan

1. Sistem siprodo berhasil dirancang untuk mengatasi permasalahan pengelolaan data penelitian, publikasi, dan pengabdian masyarakat di Telkom University Jakarta

2. Implementasi menggunakan Laravel 11 dengan Tailwind CSS menghasilkan sistem yang modern, responsive, dan mudah digunakan

3. Metode Prototype terbukti efektif untuk pengembangan sistem dengan melibatkan stakeholder sejak awal

4. Sistem berhasil mengotomasi proses rekapitulasi yang sebelumnya memakan waktu berhari-hari menjadi hanya beberapa klik

5. Dashboard monitoring memberikan visibilitas real-time terhadap produktivitas dosen

### 8.2 Saran Pengembangan Lanjutan

1. **Integrasi dengan Sistem Lain**
   - Integrasi dengan SINTA untuk auto-fetch publikasi
   - Integrasi dengan Scopus/WoS API
   - Integrasi dengan sistem HRIS untuk data dosen

2. **Fitur Tambahan**
   - Notifikasi email/WhatsApp untuk deadline
   - Mobile app untuk akses lebih mudah
   - AI-powered recommendation untuk kolaborasi penelitian
   - Analitik prediktif untuk trend penelitian

3. **Peningkatan Performa**
   - Implementasi caching untuk query berat
   - Database indexing optimization
   - CDN untuk file storage

4. **Keamanan**
   - Two-factor authentication
   - Audit trail untuk semua perubahan data
   - Regular security audit

5. **Skalabilitas**
   - Microservices architecture untuk sistem yang lebih besar
   - Load balancing untuk high availability
   - Cloud deployment (AWS/GCP/Azure)

---

## LAMPIRAN

### A. User Credentials (Development)

| Role | Email | Password |
|------|-------|----------|
| Super Admin | admin@telkomuniversity.ac.id | password |
| Kaprodi | kaprodi@telkomuniversity.ac.id | password |
| Dosen 1 | dosen1@telkomuniversity.ac.id | password |
| Dosen 2 | dosen2@telkomuniversity.ac.id | password |
| Dosen 3 | dosen3@telkomuniversity.ac.id | password |

### B. Installation Guide

```bash
# Clone repository
git clone <repository-url>
cd siprodo

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Run migrations and seeders
php artisan migrate:fresh --seed

# Build assets
npm run build

# Start development server
php artisan serve
```

### C. API Endpoints (Jika diperlukan)

```
GET    /api/penelitian          - List all penelitian
POST   /api/penelitian          - Create penelitian
GET    /api/penelitian/{id}     - Show penelitian
PUT    /api/penelitian/{id}     - Update penelitian
DELETE /api/penelitian/{id}     - Delete penelitian

GET    /api/publikasi           - List all publikasi
POST   /api/publikasi           - Create publikasi
...

GET    /api/dashboard/stats     - Get dashboard statistics
GET    /api/reports/export      - Export reports
```

---

**Dokumen ini dibuat sebagai bagian dari Capstone Design Project**
**Program Studi Sistem Informasi**
**Telkom University Jakarta**
**Tahun 2025**

