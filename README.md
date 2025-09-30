# 🎓 SIPRODA - Sistem Informasi Produktivitas Dosen
## Sistem Informasi Inventaris Penelitian, Publikasi, dan Pengabdian Masyarakat

[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-3.4+-38B2AC.svg)](https://tailwindcss.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![Status](https://img.shields.io/badge/Status-Production%20Ready-success.svg)](.)

---

## 📖 Tentang Proyek

**SIPRODA** adalah sistem informasi berbasis web yang dirancang untuk mengelola data penelitian, publikasi, dan pengabdian masyarakat dosen di **Telkom University Jakarta**. Sistem ini dikembangkan sebagai **Capstone Design Project** menggunakan **metode Prototype** untuk mengatasi permasalahan pengelolaan data manual yang memakan waktu dan tidak efisien.

### 🎯 Permasalahan yang Diselesaikan

Berdasarkan hasil wawancara dengan Ibu Kaprodi Sistem Informasi:

1. ❌ **Tidak ada alur kerja baku** → ✅ Workflow sistematis dengan verifikasi
2. ❌ **Pengelolaan manual dengan Excel** → ✅ Sistem terintegrasi berbasis web
3. ❌ **Sistem terisolasi** → ✅ Platform terpusat untuk semua data
4. ❌ **Rekapitulasi lambat (berhari-hari)** → ✅ Rekapitulasi otomatis (beberapa klik)
5. ❌ **Tidak ada monitoring** → ✅ Dashboard real-time dengan analytics

### 🎯 Tujuan Utama

1. **Centralized Data Management** - Mengelola semua data penelitian, publikasi, dan pengmas dalam satu sistem terpadu
2. **Automated Reporting** - Mengotomasi proses rekapitulasi yang sebelumnya memakan waktu berhari-hari
3. **Real-time Monitoring** - Menyediakan dashboard untuk monitoring produktivitas dosen secara real-time
4. **Role-based Access** - Sistem akses berbasis peran (Super Admin, Kaprodi, Dosen)
5. **Data Verification** - Workflow verifikasi data oleh Kaprodi/Admin

---

## ✨ Fitur Utama

### 👤 Manajemen User
- ✅ Multi-role authentication (Super Admin, Kaprodi, Dosen)
- ✅ Profile management dengan data lengkap (NIDN, NIP, kontak)
- ✅ Role-based access control (RBAC)

### 🔬 Manajemen Penelitian
- ✅ CRUD data penelitian lengkap
- ✅ Upload file proposal dan laporan
- ✅ Tracking status (Proposal, Berjalan, Selesai, Ditolak)
- ✅ Pencatatan anggota tim dan mahasiswa terlibat
- ✅ Verifikasi data oleh Kaprodi/Admin

### 📚 Manajemen Publikasi
- ✅ CRUD data publikasi (Jurnal, Prosiding, Buku, Paten, HKI)
- ✅ Pencatatan indexing (Scopus, WoS, SINTA 1-6)
- ✅ Pencatatan quartile (Q1-Q4)
- ✅ Link ke penelitian terkait
- ✅ Upload file publikasi

### 🤝 Manajemen Pengabdian Masyarakat
- ✅ CRUD data pengmas
- ✅ Pencatatan lokasi, mitra, dan jumlah peserta
- ✅ Upload proposal, laporan, dan dokumentasi
- ✅ Tracking mahasiswa terlibat

### 📊 Dashboard & Analytics
- ✅ Dashboard berbeda untuk Admin dan Dosen
- ✅ Statistik real-time (total penelitian, publikasi, pengmas)
- ✅ Grafik trend 5 tahun terakhir
- ✅ Rasio produktivitas (penelitian/dosen, publikasi/dosen, pengmas/dosen)
- ✅ Recent activities feed

### 📄 Reporting & Export
- ✅ Export data ke Excel
- ✅ Export data ke PDF
- ✅ Filter laporan (tahun, semester, jenis, status)
- ✅ Laporan produktivitas dosen
- ✅ Laporan untuk akreditasi

---

## 🛠️ Teknologi yang Digunakan

### Backend
- **PHP 8.2+** - Server-side programming language
- **Laravel 11.x** - PHP Framework (MVC Architecture)
- **MySQL/SQLite** - Relational Database
- **Composer** - Dependency Manager

### Frontend
- **Tailwind CSS 3.4+** - Utility-first CSS framework
- **Alpine.js / Livewire** - JavaScript framework
- **Blade** - Laravel template engine
- **Chart.js / ApexCharts** - Data visualization
- **Vite 6.x** - Frontend build tool

### Additional Libraries
- **Laravel Excel** - Excel export/import
- **DomPDF / Snappy** - PDF generation
- **Intervention Image** - Image processing
- **Laravel Sanctum** - API authentication (optional)

---

## 📋 Persyaratan Sistem

- PHP >= 8.2
- Composer >= 2.0
- Node.js >= 18.x
- NPM >= 9.x
- MySQL >= 8.0 atau SQLite
- Web Server (Apache/Nginx)

---

## 🚀 Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/your-username/siproda.git
cd siproda/SIPRODA
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### 3. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Configuration

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=siproda
DB_USERNAME=root
DB_PASSWORD=
```

Atau gunakan SQLite untuk development:

```env
DB_CONNECTION=sqlite
# DB_DATABASE akan otomatis dibuat di database/database.sqlite
```

### 5. Run Migrations & Seeders

```bash
# Run migrations
php artisan migrate

# Run seeders (optional - untuk data dummy)
php artisan db:seed
```

### 6. Storage Link

```bash
# Create symbolic link untuk storage
php artisan storage:link
```

### 7. Build Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### 8. Run Application

```bash
# Start development server
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

---

## 👥 Default User Credentials

Setelah menjalankan seeder, Anda dapat login dengan kredensial berikut:

| Role | Email | Password |
|------|-------|----------|
| Super Admin | admin@telkomuniversity.ac.id | password |
| Kaprodi | kaprodi@telkomuniversity.ac.id | password |
| Dosen 1 | dosen1@telkomuniversity.ac.id | password |
| Dosen 2 | dosen2@telkomuniversity.ac.id | password |
| Dosen 3 | dosen3@telkomuniversity.ac.id | password |

⚠️ **PENTING**: Ubah password default setelah login pertama kali!

---

## 📁 Struktur Database

### Tabel Utama

1. **users** - Data pengguna (dosen, kaprodi, admin)
2. **penelitian** - Data penelitian dosen
3. **publikasi** - Data publikasi dosen
4. **pengabdian_masyarakat** - Data pengabdian masyarakat

### Relasi

- `users` 1:N `penelitian`
- `users` 1:N `publikasi`
- `users` 1:N `pengabdian_masyarakat`
- `penelitian` 1:N `publikasi` (optional)

Lihat file `DOKUMENTASI_CAPSTONE.md` untuk ERD lengkap.

---

## 🎨 Color Scheme

Sistem menggunakan color palette resmi Telkom University:

| Color | Hex Code | Usage |
|-------|----------|-------|
| Telkom Red | `#a02127` | Primary color, headers, buttons |
| Telkom Green | `#10784b` | Success states, verified badges |
| Telkom Gray | `#818183` | Secondary text, borders |
| Telkom Dark | `#585858` | Primary text, headings |
| White | `#FFFFFF` | Background, cards |

---

## 📚 Dokumentasi Lengkap

Proyek ini dilengkapi dengan dokumentasi komprehensif untuk keperluan laporan Capstone:

### 📄 Dokumentasi Utama

1. **[DOKUMENTASI_CAPSTONE.md](DOKUMENTASI_CAPSTONE.md)** - 300+ baris
   - Analisis kebutuhan lengkap dari hasil wawancara
   - Perancangan sistem (ERD, flowchart, arsitektur)
   - Implementasi detail setiap komponen
   - Strategi testing dan evaluasi
   - Kesimpulan dan rekomendasi

2. **[LAPORAN_METODE_PROTOTYPE.md](LAPORAN_METODE_PROTOTYPE.md)** - 300+ baris
   - Penjelasan lengkap metode Prototype
   - 8 tahapan pengembangan dengan detail
   - Evaluasi setiap iterasi
   - Feedback stakeholder
   - Lessons learned dan best practices

3. **[TOOLS_DAN_TEKNOLOGI.md](TOOLS_DAN_TEKNOLOGI.md)** - 300+ baris
   - Daftar lengkap semua tools yang digunakan
   - Alasan pemilihan setiap teknologi
   - Konfigurasi dan setup detail
   - Technology decision matrix
   - Learning resources

4. **[PANDUAN_INSTALASI.md](PANDUAN_INSTALASI.md)** - 300+ baris
   - Instalasi step-by-step untuk Windows/macOS/Linux
   - Konfigurasi database (MySQL/SQLite/PostgreSQL)
   - Troubleshooting common issues
   - Checklist instalasi lengkap

5. **[SUMMARY_CAPSTONE.md](SUMMARY_CAPSTONE.md)** - 300+ baris
   - Executive summary proyek
   - Ringkasan teknologi dan fitur
   - Hasil yang dicapai
   - Impact analysis
   - Future enhancements

6. **[CHECKLIST_LAPORAN_CAPSTONE.md](CHECKLIST_LAPORAN_CAPSTONE.md)** - 300+ baris
   - Checklist lengkap untuk laporan Capstone
   - Progress tracking
   - Timeline pengembangan
   - Next steps

7. **[SIPRODA/COMMANDS.md](SIPRODA/COMMANDS.md)** - 300+ baris
   - Daftar lengkap command Laravel
   - Command untuk development
   - Command untuk deployment
   - Useful aliases dan workflows

### 📊 Total Dokumentasi

- **7 file dokumentasi** dengan total **2100+ baris**
- **Semua aspek** pengembangan tercakup
- **Siap digunakan** untuk laporan Capstone
- **Format Markdown** yang mudah dibaca dan diedit

---

## 🧪 Testing

### Run Unit Tests

```bash
php artisan test
```

### Run Specific Test

```bash
php artisan test --filter PenelitianTest
```

### Code Coverage

```bash
php artisan test --coverage
```

---

## 🔒 Security

- ✅ Password hashing menggunakan bcrypt
- ✅ CSRF protection
- ✅ SQL injection prevention (Eloquent ORM)
- ✅ XSS protection
- ✅ Role-based access control
- ✅ File upload validation
- ✅ Session management

---

## 🤝 Contributing

Kontribusi sangat diterima! Silakan ikuti langkah berikut:

1. Fork repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

---

## 📝 License

Project ini dilisensikan di bawah [MIT License](LICENSE).

---

## 👨‍💻 Developer

**Capstone Design Project**  
Program Studi Sistem Informasi  
Telkom University Jakarta  
Tahun 2025

---

## 📞 Support

Jika Anda mengalami masalah atau memiliki pertanyaan:

- 📧 Email: support@telkomuniversity.ac.id
- 📱 WhatsApp: +62 812-3456-7890
- 🐛 Issues: [GitHub Issues](https://github.com/your-username/siproda/issues)

---

## 🙏 Acknowledgments

- Ibu Kaprodi Sistem Informasi Telkom University Jakarta
- Tim Dosen Program Studi Sistem Informasi
- Laravel Community
- Tailwind CSS Team

---

## 📸 Screenshots

### Dashboard Admin
![Dashboard Admin](docs/screenshots/dashboard-admin.png)

### Dashboard Dosen
![Dashboard Dosen](docs/screenshots/dashboard-dosen.png)

### Manajemen Penelitian
![Penelitian](docs/screenshots/penelitian.png)

### Laporan Produktivitas
![Reports](docs/screenshots/reports.png)

---

## 🗺️ Roadmap

### Version 1.0 (Current)
- ✅ Basic CRUD operations
- ✅ Role-based access control
- ✅ Dashboard & analytics
- ✅ Export to Excel/PDF

### Version 1.1 (Planned)
- ⏳ Email notifications
- ⏳ Advanced search & filters
- ⏳ Bulk import from Excel
- ⏳ API for mobile app

### Version 2.0 (Future)
- ⏳ Integration with SINTA API
- ⏳ Integration with Scopus API
- ⏳ AI-powered recommendations
- ⏳ Mobile application (Flutter)
- ⏳ Predictive analytics

---

**Made with ❤️ for Telkom University Jakarta**

