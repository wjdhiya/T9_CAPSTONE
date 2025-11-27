# 🛠️ TOOLS DAN TEKNOLOGI YANG DIGUNAKAN
## Sistem Informasi Produktivitas Dosen (siprodo)

---

## 📋 Daftar Isi

1. [Backend Technologies](#backend-technologies)
2. [Frontend Technologies](#frontend-technologies)
3. [Database](#database)
4. [Development Tools](#development-tools)
5. [Libraries & Packages](#libraries--packages)
6. [Deployment & DevOps](#deployment--devops)
7. [Testing Tools](#testing-tools)
8. [Design Tools](#design-tools)

---

## 1. BACKEND TECHNOLOGIES

### 1.1 PHP 8.2+

**Fungsi:** Server-side programming language

**Alasan Pemilihan:**
- ✅ Mature dan stable untuk web development
- ✅ Ekosistem yang luas
- ✅ Performance yang baik dengan PHP 8.2
- ✅ Support untuk modern features (typed properties, attributes, dll)

**Features yang Digunakan:**
- Type declarations
- Arrow functions
- Named arguments
- Attributes
- Match expressions

**Dokumentasi:** [php.net](https://www.php.net/)

---

### 1.2 Laravel 11.x

**Fungsi:** PHP Framework (MVC Architecture)

**Alasan Pemilihan:**
- ✅ Framework PHP paling populer
- ✅ Dokumentasi lengkap dan komunitas besar
- ✅ Built-in features yang lengkap (Auth, ORM, Queue, dll)
- ✅ Eloquent ORM yang powerful
- ✅ Blade templating engine yang elegant

**Features yang Digunakan:**

| Feature | Fungsi dalam siprodo |
|---------|----------------------|
| **Eloquent ORM** | Database operations, relationships |
| **Blade Templates** | View rendering |
| **Middleware** | Authentication, authorization |
| **Validation** | Input validation |
| **File Storage** | Upload file penelitian/publikasi |
| **Migrations** | Database version control |
| **Seeders** | Data dummy untuk testing |
| **Policies** | Authorization logic |
| **Events & Listeners** | Notifikasi sistem |

**Struktur MVC:**
```
app/
├── Models/              # Data layer
├── Controllers/         # Business logic
├── Middleware/          # Request filtering
└── Policies/            # Authorization

resources/
└── views/               # Presentation layer

routes/
└── web.php              # Route definitions
```

**Dokumentasi:** [laravel.com/docs](https://laravel.com/docs)

---

### 1.3 Composer 2.x

**Fungsi:** PHP Dependency Manager

**Alasan Pemilihan:**
- ✅ Standard untuk PHP dependency management
- ✅ Autoloading PSR-4
- ✅ Package management yang mudah

**Packages yang Diinstall:**
```json
{
  "require": {
    "laravel/framework": "^11.0",
    "laravel/tinker": "^2.9",
    "maatwebsite/excel": "^3.1",
    "barryvdh/laravel-dompdf": "^2.0"
  },
  "require-dev": {
    "phpunit/phpunit": "^11.0",
    "laravel/pint": "^1.13",
    "nunomaduro/collision": "^8.1"
  }
}
```

**Dokumentasi:** [getcomposer.org](https://getcomposer.org/)

---

## 2. FRONTEND TECHNOLOGIES

### 2.1 Tailwind CSS 3.4+

**Fungsi:** Utility-first CSS Framework

**Alasan Pemilihan:**
- ✅ Rapid development dengan utility classes
- ✅ Customizable dengan config file
- ✅ Responsive design yang mudah
- ✅ File size kecil dengan purging
- ✅ Modern dan trendy

**Konfigurasi Custom:**
```javascript
// tailwind.config.js
export default {
  theme: {
    extend: {
      colors: {
        'telkom-red': '#a02127',
        'telkom-green': '#10784b',
        'telkom-gray': '#818183',
        'telkom-dark': '#585858',
      },
    },
  },
}
```

**Komponen yang Dibuat:**
- Cards
- Buttons
- Forms
- Tables
- Modals
- Alerts
- Badges
- Navigation

**Dokumentasi:** [tailwindcss.com](https://tailwindcss.com/)

---

### 2.2 Blade Template Engine

**Fungsi:** Laravel's templating engine

**Alasan Pemilihan:**
- ✅ Native Laravel, tidak perlu setup tambahan
- ✅ Syntax yang clean dan readable
- ✅ Component-based architecture
- ✅ Inheritance dan sections
- ✅ Directives yang powerful

**Features yang Digunakan:**
```blade
{{-- Layouts --}}
@extends('layouts.app')
@section('content')

{{-- Components --}}
<x-card title="Dashboard">
    <x-slot name="header">...</x-slot>
</x-card>

{{-- Directives --}}
@auth
@can('verify', $penelitian)
@foreach($items as $item)
@if($condition)

{{-- Includes --}}
@include('partials.header')
```

**Dokumentasi:** [laravel.com/docs/blade](https://laravel.com/docs/blade)

---

### 2.3 Alpine.js / Livewire

**Fungsi:** JavaScript framework untuk interaktivitas

**Alasan Pemilihan:**
- ✅ Lightweight (15kb)
- ✅ Syntax mirip Vue.js
- ✅ Tidak perlu build step
- ✅ Perfect untuk small interactions

**Contoh Penggunaan:**
```html
<!-- Dropdown -->
<div x-data="{ open: false }">
    <button @click="open = !open">Menu</button>
    <div x-show="open">...</div>
</div>

<!-- Modal -->
<div x-data="{ show: false }">
    <button @click="show = true">Open Modal</button>
    <div x-show="show" x-cloak>...</div>
</div>
```

**Dokumentasi:** [alpinejs.dev](https://alpinejs.dev/)

---

### 2.4 Chart.js / ApexCharts

**Fungsi:** Data visualization library

**Alasan Pemilihan:**
- ✅ Mudah digunakan
- ✅ Responsive
- ✅ Banyak jenis chart
- ✅ Customizable

**Charts yang Digunakan:**
- Line chart (Trend produktivitas)
- Bar chart (Perbandingan per tahun_akademik)
- Pie chart (Distribusi jenis penelitian)
- Doughnut chart (Status verifikasi)

**Contoh Implementasi:**
```javascript
new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['2020', '2021', '2022', '2023', '2024'],
        datasets: [{
            label: 'Penelitian',
            data: [12, 19, 15, 25, 22]
        }]
    }
});
```

**Dokumentasi:** [chartjs.org](https://www.chartjs.org/)

---

### 2.5 Vite 6.x

**Fungsi:** Frontend build tool

**Alasan Pemilihan:**
- ✅ Sangat cepat (HMR instant)
- ✅ Native ES modules
- ✅ Built-in support untuk Laravel
- ✅ Modern dan efficient

**Konfigurasi:**
```javascript
// vite.config.js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
```

**Dokumentasi:** [vitejs.dev](https://vitejs.dev/)

---

## 3. DATABASE

### 3.1 MySQL 8.0+

**Fungsi:** Relational Database Management System

**Alasan Pemilihan:**
- ✅ Reliable dan proven
- ✅ Performance yang baik
- ✅ Support untuk complex queries
- ✅ ACID compliance
- ✅ Widely used

**Database Schema:**
- users (5 users)
- penelitian (3 tables)
- publikasi (3 tables)
- pengabdian_masyarakat (3 tables)
- migrations (tracking)

**Optimizations:**
- Indexing pada foreign keys
- Indexing pada search fields
- Soft deletes untuk data integrity

**Dokumentasi:** [mysql.com](https://dev.mysql.com/doc/)

---

### 3.2 SQLite (Development)

**Fungsi:** Lightweight database untuk development

**Alasan Pemilihan:**
- ✅ Zero configuration
- ✅ File-based, mudah di-reset
- ✅ Perfect untuk testing
- ✅ Fast untuk development

**Dokumentasi:** [sqlite.org](https://www.sqlite.org/)

---

## 4. DEVELOPMENT TOOLS

### 4.1 Visual Studio Code

**Fungsi:** Code Editor

**Extensions yang Digunakan:**
- Laravel Extension Pack
- PHP Intelephense
- Tailwind CSS IntelliSense
- Blade Formatter
- GitLens
- ESLint
- Prettier

**Dokumentasi:** [code.visualstudio.com](https://code.visualstudio.com/)

---

### 4.2 Git & GitHub

**Fungsi:** Version Control System

**Workflow:**
```bash
# Feature branch
git checkout -b feature/penelitian-crud

# Commit
git add .
git commit -m "feat: add penelitian CRUD"

# Push
git push origin feature/penelitian-crud

# Merge via Pull Request
```

**Dokumentasi:** [git-scm.com](https://git-scm.com/)

---

### 4.3 Laravel Pint

**Fungsi:** Code Formatter (PHP)

**Alasan Pemilihan:**
- ✅ Official Laravel code style
- ✅ Zero configuration
- ✅ Fast

**Usage:**
```bash
./vendor/bin/pint
```

**Dokumentasi:** [laravel.com/docs/pint](https://laravel.com/docs/pint)

---

### 4.4 Postman

**Fungsi:** API Testing Tool

**Digunakan untuk:**
- Testing API endpoints
- Debugging requests
- Documentation

**Dokumentasi:** [postman.com](https://www.postman.com/)

---

## 5. LIBRARIES & PACKAGES

### 5.1 Laravel Excel (maatwebsite/excel)

**Fungsi:** Excel import/export

**Features:**
- Export data penelitian ke Excel
- Export laporan produktivitas
- Custom styling
- Multiple sheets

**Contoh:**
```php
return Excel::download(
    new PenelitianExport($year), 
    'penelitian-2024.xlsx'
);
```

**Dokumentasi:** [laravel-excel.com](https://laravel-excel.com/)

---

### 5.2 DomPDF (barryvdh/laravel-dompdf)

**Fungsi:** PDF Generation

**Features:**
- Generate laporan PDF
- Custom templates
- Header/Footer
- Page numbering

**Contoh:**
```php
$pdf = PDF::loadView('reports.penelitian', $data);
return $pdf->download('laporan.pdf');
```

**Dokumentasi:** [github.com/barryvdh/laravel-dompdf](https://github.com/barryvdh/laravel-dompdf)

---

### 5.3 Intervention Image

**Fungsi:** Image Processing

**Features:**
- Resize uploaded images
- Thumbnail generation
- Image optimization

**Dokumentasi:** [image.intervention.io](http://image.intervention.io/)

---

## 6. DEPLOYMENT & DEVOPS

### 6.1 Laravel Forge (Optional)

**Fungsi:** Server management & deployment

**Features:**
- One-click deployment
- SSL certificates
- Database management
- Scheduled jobs

**Dokumentasi:** [forge.laravel.com](https://forge.laravel.com/)

---

### 6.2 Docker (Optional)

**Fungsi:** Containerization

**Services:**
- PHP 8.2
- MySQL 8.0
- Nginx
- Redis

**Dokumentasi:** [docker.com](https://www.docker.com/)

---

## 7. TESTING TOOLS

### 7.1 PHPUnit

**Fungsi:** PHP Testing Framework

**Test Types:**
- Unit tests
- Feature tests
- Integration tests

**Contoh:**
```php
public function test_dosen_can_create_penelitian()
{
    $user = User::factory()->create(['role' => 'dosen']);
    
    $response = $this->actingAs($user)
        ->post('/penelitian', $data);
    
    $response->assertStatus(302);
    $this->assertDatabaseHas('penelitian', ['judul' => 'Test']);
}
```

**Dokumentasi:** [phpunit.de](https://phpunit.de/)

---

### 7.2 Laravel Dusk (Optional)

**Fungsi:** Browser Testing

**Features:**
- End-to-end testing
- Browser automation
- Screenshot on failure

**Dokumentasi:** [laravel.com/docs/dusk](https://laravel.com/docs/dusk)

---

## 8. DESIGN TOOLS

### 8.1 Figma

**Fungsi:** UI/UX Design

**Digunakan untuk:**
- Wireframing
- Mockups
- Prototyping
- Design system

**Dokumentasi:** [figma.com](https://www.figma.com/)

---

### 8.2 Draw.io / Lucidchart

**Fungsi:** Diagram & Flowchart

**Digunakan untuk:**
- ERD
- Flowchart
- Architecture diagram
- Use case diagram

**Dokumentasi:** [draw.io](https://www.draw.io/)

---

## 📊 SUMMARY TABLE

### Backend Stack

| Technology | Version | Purpose |
|------------|---------|---------|
| PHP | 8.2+ | Programming Language |
| Laravel | 11.x | Web Framework |
| MySQL | 8.0+ | Database |
| Composer | 2.x | Dependency Manager |

### Frontend Stack

| Technology | Version | Purpose |
|------------|---------|---------|
| Tailwind CSS | 3.4+ | CSS Framework |
| Alpine.js | 3.x | JavaScript Framework |
| Blade | Laravel 11 | Template Engine |
| Chart.js | 4.x | Data Visualization |
| Vite | 6.x | Build Tool |

### Development Tools

| Tool | Purpose |
|------|---------|
| VS Code | Code Editor |
| Git | Version Control |
| Postman | API Testing |
| Laravel Pint | Code Formatter |
| PHPUnit | Testing Framework |

### Libraries

| Library | Purpose |
|---------|---------|
| Laravel Excel | Excel Export |
| DomPDF | PDF Generation |
| Intervention Image | Image Processing |

---

## 🎯 TECHNOLOGY DECISION MATRIX

### Why Laravel?

| Criteria | Score (1-5) | Notes |
|----------|-------------|-------|
| Learning Curve | 4 | Well documented |
| Community Support | 5 | Very large community |
| Features | 5 | Complete ecosystem |
| Performance | 4 | Good with optimization |
| Security | 5 | Built-in security features |
| **Total** | **23/25** | **Excellent Choice** |

### Why Tailwind CSS?

| Criteria | Score (1-5) | Notes |
|----------|-------------|-------|
| Development Speed | 5 | Very fast |
| Customization | 5 | Highly customizable |
| File Size | 4 | Small with purging |
| Learning Curve | 3 | Need to learn utilities |
| Community | 5 | Growing rapidly |
| **Total** | **22/25** | **Excellent Choice** |

---

## 📚 LEARNING RESOURCES

### Laravel
- [Laravel Documentation](https://laravel.com/docs)
- [Laracasts](https://laracasts.com)
- [Laravel News](https://laravel-news.com)

### Tailwind CSS
- [Tailwind Documentation](https://tailwindcss.com/docs)
- [Tailwind UI](https://tailwindui.com)
- [Tailwind Components](https://tailwindcomponents.com)

### PHP
- [PHP Documentation](https://www.php.net/docs.php)
- [PHP The Right Way](https://phptherightway.com)

---

**Dokumen ini merupakan bagian dari Laporan Capstone Design Project**  
**Program Studi Sistem Informasi**  
**Telkom University Jakarta**  
**tahun_akademik 2025**

