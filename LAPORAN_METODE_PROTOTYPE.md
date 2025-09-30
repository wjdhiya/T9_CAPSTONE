# LAPORAN METODE PROTOTYPE
## Pengembangan Sistem Informasi Produktivitas Dosen (SIPRODA)
### Telkom University Jakarta

---

## BAB I: PENDAHULUAN

### 1.1 Latar Belakang Pemilihan Metode

Metode **Prototype** dipilih untuk pengembangan sistem SIPRODA karena beberapa alasan strategis:

1. **Ketidakpastian Kebutuhan Awal**
   - Stakeholder (Kaprodi) belum memiliki gambaran jelas tentang sistem yang diinginkan
   - Kebutuhan masih bersifat umum dan perlu eksplorasi lebih lanjut
   - Tidak ada sistem serupa sebelumnya yang bisa dijadikan referensi

2. **Kebutuhan Feedback Cepat**
   - Stakeholder perlu melihat visualisasi sistem sejak awal
   - Perlu validasi konsep sebelum pengembangan penuh
   - Memungkinkan perubahan requirement di tengah pengembangan

3. **Kompleksitas Domain**
   - Domain penelitian, publikasi, dan pengmas memiliki banyak variabel
   - Perlu pemahaman mendalam tentang workflow yang ada
   - Memerlukan iterasi untuk menyempurnakan fitur

4. **Keterbatasan Waktu**
   - Timeline pengembangan yang ketat (12 minggu)
   - Perlu fokus pada fitur prioritas tinggi
   - Iterasi cepat lebih efektif daripada waterfall

### 1.2 Karakteristik Metode Prototype

Metode Prototype memiliki karakteristik:

- **Iterative**: Pengembangan dilakukan dalam siklus berulang
- **Incremental**: Fitur ditambahkan secara bertahap
- **User-Centered**: Fokus pada kebutuhan dan feedback pengguna
- **Flexible**: Mudah beradaptasi dengan perubahan requirement
- **Visual**: Menghasilkan working model yang bisa dilihat dan diuji

---

## BAB II: TAHAPAN METODE PROTOTYPE

### 2.1 Tahap 1: Requirements Gathering (Pengumpulan Kebutuhan)

#### 2.1.1 Aktivitas

**Wawancara dengan Stakeholder**
- Tanggal: [Sesuai dengan tanggal wawancara Anda]
- Narasumber: Ibu Kaprodi Sistem Informasi
- Durasi: 60 menit
- Metode: Wawancara semi-terstruktur

**Pertanyaan Kunci:**
1. Bagaimana alur kerja saat ini dalam mengelola data penelitian?
2. Apa saja kendala yang dihadapi?
3. Berapa lama waktu yang dibutuhkan untuk rekapitulasi?
4. Fitur apa yang paling dibutuhkan?
5. Siapa saja pengguna sistem?

**Hasil Wawancara:**

| Temuan | Detail |
|--------|--------|
| Alur Kerja | Tidak ada SOP baku, pengumpulan data ad-hoc |
| Pengelolaan Data | Manual menggunakan Excel yang tersebar |
| Waktu Rekapitulasi | Berhari-hari hingga berminggu-minggu |
| Sistem Existing | Sistem pengmas individual, tidak terintegrasi |
| Pain Points | Tidak ada monitoring, verifikasi manual, data tidak akurat |

**Analisis Kebutuhan:**

Dari hasil wawancara, diidentifikasi 5 akar permasalahan utama:

1. Tidak ada alur kerja baku
2. Pengelolaan data manual
3. Sistem terisolasi
4. Rekapitulasi lambat
5. Tidak ada monitoring

#### 2.1.2 Output

- ✅ Dokumen hasil wawancara
- ✅ Analisis akar permasalahan
- ✅ Functional requirements (FR-01 s/d FR-06)
- ✅ Non-functional requirements (NFR-01 s/d NFR-05)
- ✅ User stories

**Contoh User Stories:**

```
Sebagai Dosen,
Saya ingin dapat menginput data penelitian dengan mudah,
Sehingga saya tidak perlu mengirim Excel ke Kaprodi.

Sebagai Kaprodi,
Saya ingin dapat melihat produktivitas semua dosen,
Sehingga saya dapat membuat laporan dengan cepat.

Sebagai Admin,
Saya ingin dapat memverifikasi data yang diinput dosen,
Sehingga data yang dilaporkan akurat.
```

#### 2.1.3 Durasi
**1 Minggu** (Minggu 1)

---

### 2.2 Tahap 2: Quick Design (Perancangan Cepat)

#### 2.2.1 Aktivitas

**Database Design**
- Identifikasi entitas utama: User, Penelitian, Publikasi, Pengmas
- Definisi atribut setiap entitas
- Penentuan relasi antar entitas
- Pembuatan ERD (Entity Relationship Diagram)

**Architecture Design**
- Pemilihan arsitektur: MVC (Model-View-Controller)
- Pemilihan framework: Laravel 11
- Pemilihan database: MySQL/SQLite
- Pemilihan frontend: Tailwind CSS + Blade

**UI/UX Design**
- Wireframing halaman utama
- Mockup dashboard
- Design system (color palette, typography, components)
- User flow diagram

**Workflow Design**
- Flowchart proses input data
- Flowchart proses verifikasi
- Flowchart proses reporting

#### 2.2.2 Output

**1. ERD (Entity Relationship Diagram)**

```
┌─────────────────┐
│     USERS       │
├─────────────────┤
│ PK id           │
│    name         │
│    email        │
│    role         │
│    nidn         │
│    ...          │
└────────┬────────┘
         │ 1:N
    ┌────┴────┬────────────┬────────────┐
    │         │            │            │
┌───▼──────┐ ┌▼──────────┐ ┌▼──────────┐
│PENELITIAN│ │ PUBLIKASI │ │ PENGMAS   │
└──────────┘ └───────────┘ └───────────┘
```

**2. Wireframe Dashboard**

```
┌─────────────────────────────────────────────────┐
│  SIPRODA - Dashboard                    [User▼] │
├─────────────────────────────────────────────────┤
│ [Dashboard] [Penelitian] [Publikasi] [Pengmas] │
├─────────────────────────────────────────────────┤
│                                                  │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐        │
│  │ Total    │ │ Total    │ │ Total    │        │
│  │Penelitian│ │Publikasi │ │ Pengmas  │        │
│  │   15     │ │   23     │ │   18     │        │
│  └──────────┘ └──────────┘ └──────────┘        │
│                                                  │
│  ┌────────────────────────────────────────┐    │
│  │  Grafik Produktivitas 5 Tahun          │    │
│  │  [Chart visualization]                 │    │
│  └────────────────────────────────────────┘    │
│                                                  │
│  Recent Activities:                             │
│  • Dosen A menambah penelitian baru             │
│  • Dosen B publikasi terverifikasi              │
│  └────────────────────────────────────────┘    │
└─────────────────────────────────────────────────┘
```

**3. Color Palette**

| Color | Hex | Usage |
|-------|-----|-------|
| Primary | #a02127 | Buttons, Headers |
| Secondary | #10784b | Success, Verified |
| Neutral | #818183 | Text, Borders |
| Dark | #585858 | Headings |
| White | #FFFFFF | Background |

**4. Flowchart Input Data**

```
[Start] → [Login] → [Pilih Menu] → [Form Input]
   ↓
[Validasi] → [Simpan ke DB] → [Status: Pending]
   ↓
[Notifikasi ke Kaprodi] → [End]
```

#### 2.2.3 Durasi
**1 Minggu** (Minggu 2)

---

### 2.3 Tahap 3: Build Prototype (Pembangunan Prototype)

#### 2.3.1 Iterasi 1 - Core Features

**Aktivitas:**

1. **Setup Environment**
   ```bash
   composer create-project laravel/laravel siproda
   npm install tailwindcss
   ```

2. **Database Implementation**
   - Membuat migrations untuk semua tabel
   - Membuat models dengan relationships
   - Membuat seeders untuk data dummy

3. **Authentication**
   - Implementasi login/logout
   - Role-based middleware
   - Session management

4. **CRUD Penelitian**
   - Form input penelitian
   - List penelitian
   - Detail penelitian
   - Edit/Delete penelitian

5. **Basic Dashboard**
   - Statistik sederhana
   - Recent activities

**Fitur yang Diimplementasikan:**

| No | Fitur | Status |
|----|-------|--------|
| 1 | Login/Logout | ✅ Done |
| 2 | Dashboard Dosen | ✅ Done |
| 3 | Dashboard Admin | ✅ Done |
| 4 | CRUD Penelitian | ✅ Done |
| 5 | File Upload | ✅ Done |
| 6 | Role-based Access | ✅ Done |

**Teknologi yang Digunakan:**

```
Backend:
- Laravel 11.x
- PHP 8.2
- MySQL

Frontend:
- Tailwind CSS 3.4
- Blade Templates
- Alpine.js (untuk interaktivitas)

Tools:
- Vite (build tool)
- Composer (dependency manager)
- NPM (package manager)
```

#### 2.3.2 Output Iterasi 1

- ✅ Prototype yang bisa dijalankan
- ✅ Login system dengan 3 role
- ✅ CRUD penelitian lengkap
- ✅ Dashboard sederhana
- ✅ File upload untuk proposal/laporan

**Screenshot:**
- Login page
- Dashboard dosen
- Form input penelitian
- List penelitian

#### 2.3.3 Durasi
**2 Minggu** (Minggu 3-4)

---

### 2.4 Tahap 4: Customer Evaluation (Evaluasi Prototype)

#### 2.4.1 Aktivitas

**Demo Prototype**
- Tanggal: [Sesuai jadwal Anda]
- Peserta: Ibu Kaprodi, Tim Developer
- Durasi: 90 menit
- Metode: Live demo + Q&A

**Skenario Demo:**

1. **Login sebagai Dosen**
   - Menampilkan dashboard dosen
   - Input data penelitian baru
   - Upload file proposal
   - Lihat status verifikasi

2. **Login sebagai Kaprodi**
   - Menampilkan dashboard admin
   - Lihat semua penelitian
   - Verifikasi data penelitian
   - Lihat statistik

**Feedback Collection**

Metode pengumpulan feedback:
- Observasi langsung saat demo
- Kuesioner evaluasi
- Diskusi terbuka
- Catatan perbaikan

#### 2.4.2 Hasil Evaluasi

**Feedback Positif:**

| No | Feedback | Kategori |
|----|----------|----------|
| 1 | UI modern dan mudah digunakan | UI/UX |
| 2 | Proses input lebih cepat dari Excel | Functionality |
| 3 | Dashboard informatif | Features |
| 4 | Color scheme sesuai branding | Design |

**Feedback Perbaikan:**

| No | Feedback | Prioritas | Kategori |
|----|----------|-----------|----------|
| 1 | Perlu fitur publikasi dan pengmas | High | Features |
| 2 | Grafik produktivitas perlu ditambah | High | Analytics |
| 3 | Export ke Excel diperlukan | High | Reporting |
| 4 | Filter dan search perlu ditingkatkan | Medium | UX |
| 5 | Notifikasi email untuk verifikasi | Low | Enhancement |

**Approval Status:**
✅ **APPROVED** untuk lanjut ke iterasi berikutnya dengan perbaikan

#### 2.4.3 Durasi
**3 Hari** (Minggu 5)

---

### 2.5 Tahap 5: Refine Prototype (Perbaikan Prototype)

#### 2.5.1 Aktivitas

Berdasarkan feedback evaluasi, dilakukan perbaikan:

**1. Penambahan Fitur Publikasi**
- CRUD publikasi lengkap
- Field untuk indexing (Scopus, SINTA, dll)
- Field untuk quartile
- Link ke penelitian terkait

**2. Penambahan Fitur Pengmas**
- CRUD pengabdian masyarakat
- Field lokasi, mitra, peserta
- Upload dokumentasi

**3. Enhancement Dashboard**
- Grafik trend 5 tahun
- Rasio produktivitas
- Filter by year/semester
- Recent activities feed

**4. Fitur Export**
- Export to Excel (Laravel Excel)
- Export to PDF (DomPDF)
- Custom report templates

**5. Improvement UX**
- Advanced search
- Filter multi-criteria
- Pagination
- Loading states

#### 2.5.2 Output

- ✅ Fitur publikasi lengkap
- ✅ Fitur pengmas lengkap
- ✅ Dashboard dengan grafik
- ✅ Export Excel/PDF
- ✅ Advanced search & filter

#### 2.5.3 Durasi
**1 Minggu** (Minggu 5-6)

---

### 2.6 Tahap 6: Iteration (Iterasi Lanjutan)

#### 2.6.1 Iterasi 2 - Advanced Features

**Aktivitas:**

1. **Verifikasi Workflow**
   - Implementasi status verifikasi
   - Catatan verifikasi
   - History verifikasi

2. **Analytics Enhancement**
   - Chart.js integration
   - Productivity metrics
   - Comparative analysis

3. **Reporting System**
   - Template laporan akreditasi
   - Custom date range
   - Multi-format export

4. **Performance Optimization**
   - Database indexing
   - Query optimization
   - Caching implementation

**Fitur Tambahan:**

| No | Fitur | Status |
|----|-------|--------|
| 1 | Verifikasi dengan catatan | ✅ Done |
| 2 | History verifikasi | ✅ Done |
| 3 | Chart produktivitas | ✅ Done |
| 4 | Laporan akreditasi | ✅ Done |
| 5 | Performance optimization | ✅ Done |

#### 2.6.2 Durasi
**2 Minggu** (Minggu 7-8)

---

### 2.7 Tahap 7: Final Evaluation

#### 2.7.1 Aktivitas

**Demo Final**
- Tanggal: [Sesuai jadwal]
- Peserta: Ibu Kaprodi, Dosen, Tim Developer
- Durasi: 120 menit

**Test Scenarios:**

1. **Scenario 1: Input Data Lengkap**
   - Dosen input penelitian, publikasi, pengmas
   - Upload semua file
   - Submit untuk verifikasi

2. **Scenario 2: Verifikasi Data**
   - Kaprodi review data
   - Approve/reject dengan catatan
   - Notifikasi ke dosen

3. **Scenario 3: Generate Report**
   - Filter data by year/semester
   - Export to Excel
   - Export to PDF

4. **Scenario 4: View Analytics**
   - Dashboard produktivitas
   - Grafik trend
   - Rasio produktivitas

**Acceptance Criteria:**

| No | Kriteria | Status |
|----|----------|--------|
| 1 | Semua fitur berfungsi | ✅ Pass |
| 2 | UI responsive | ✅ Pass |
| 3 | Data akurat | ✅ Pass |
| 4 | Performa baik (<3s) | ✅ Pass |
| 5 | Export berhasil | ✅ Pass |

**Final Approval:**
✅ **APPROVED** untuk deployment

#### 2.7.2 Durasi
**3 Hari** (Minggu 9)

---

### 2.8 Tahap 8: Finalization

#### 2.8.1 Aktivitas

**1. Code Refactoring**
- Clean code principles
- Remove unused code
- Optimize queries
- Add comments

**2. Testing**
- Unit testing
- Integration testing
- User acceptance testing
- Performance testing

**3. Documentation**
- Technical documentation
- User manual
- API documentation
- Deployment guide

**4. Deployment Preparation**
- Server setup
- Database migration
- Environment configuration
- SSL certificate

**5. User Training**
- Training untuk Kaprodi
- Training untuk Dosen
- Training untuk Admin
- Q&A session

#### 2.8.2 Output

- ✅ Clean codebase
- ✅ Test coverage >80%
- ✅ Complete documentation
- ✅ Deployment ready
- ✅ Trained users

#### 2.8.3 Durasi
**2 Minggu** (Minggu 9-10)

---

## BAB III: EVALUASI METODE PROTOTYPE

### 3.1 Kelebihan yang Dialami

1. **Feedback Cepat**
   - Stakeholder bisa melihat sistem sejak awal
   - Perubahan requirement mudah diakomodasi
   - Kesalahan terdeteksi lebih awal

2. **Fleksibilitas Tinggi**
   - Mudah menambah/mengubah fitur
   - Iterasi cepat
   - Adaptif terhadap perubahan

3. **User Involvement**
   - Stakeholder terlibat aktif
   - Ownership tinggi
   - Acceptance lebih mudah

4. **Risk Mitigation**
   - Risiko kegagalan lebih rendah
   - Validasi konsep sejak awal
   - Budget lebih terkontrol

### 3.2 Tantangan yang Dihadapi

1. **Scope Creep**
   - Stakeholder terus menambah requirement
   - Perlu manajemen ekspektasi yang baik
   - Timeline bisa meleset

2. **Documentation**
   - Fokus pada coding, dokumentasi terabaikan
   - Perlu disiplin mendokumentasikan setiap iterasi

3. **Technical Debt**
   - Kode cepat bisa kurang optimal
   - Perlu refactoring berkala

### 3.3 Lessons Learned

1. **Communication is Key**
   - Regular meeting dengan stakeholder penting
   - Dokumentasi feedback harus detail
   - Manage expectation sejak awal

2. **Prioritization Matters**
   - Fokus pada fitur high-priority
   - MVP (Minimum Viable Product) dulu
   - Enhancement bisa iterasi berikutnya

3. **Testing Early**
   - Jangan tunggu sampai akhir
   - Test setiap iterasi
   - User testing sangat valuable

---

## BAB IV: KESIMPULAN

### 4.1 Kesimpulan

Metode Prototype terbukti **sangat efektif** untuk pengembangan sistem SIPRODA karena:

1. Memungkinkan stakeholder melihat dan mengevaluasi sistem sejak awal
2. Fleksibel terhadap perubahan requirement
3. Mengurangi risiko kegagalan proyek
4. Menghasilkan sistem yang sesuai kebutuhan user
5. Timeline pengembangan lebih terkontrol

### 4.2 Rekomendasi

Metode Prototype **direkomendasikan** untuk proyek dengan karakteristik:

- ✅ Requirement belum jelas di awal
- ✅ Perlu feedback cepat dari user
- ✅ Domain kompleks yang perlu eksplorasi
- ✅ Timeline ketat
- ✅ Stakeholder bisa terlibat aktif

Metode Prototype **tidak direkomendasikan** untuk:

- ❌ Requirement sudah sangat jelas dan fix
- ❌ Proyek berskala sangat besar
- ❌ Stakeholder tidak bisa terlibat aktif
- ❌ Perlu dokumentasi formal sejak awal

---

**Dokumen ini merupakan bagian dari Laporan Capstone Design Project**  
**Program Studi Sistem Informasi**  
**Telkom University Jakarta**  
**Tahun 2025**

