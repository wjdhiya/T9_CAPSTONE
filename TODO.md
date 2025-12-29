# TODO: Perbaikan Export Data Penelitian dan Pengabdian Masyarakat

## Progress Checklist

### 1. Analisis Masalah ✅
- [x] Identifikasi root cause: format tahun_akademik vs query filter
- [x] Temukan lokasi masalah di ReportController dan PenelitianController
- [x] Analisis format data: "2024/2025" vs input "2024"

### 2. Perbaikan ReportController.php
- [ ] **getStatistics() method** - Fix Penelitian query (line ~47)
- [ ] **getStatistics() method** - Fix Publikasi query (line ~67) 
- [ ] **getStatistics() method** - Fix Pengmas query (line ~89)
- [ ] **exportExcel() method** - Fix Penelitian query (line ~129)
- [ ] **exportExcel() method** - Fix Publikasi query (line ~153)
- [ ] **exportExcel() method** - Fix Pengmas query (line ~175)
- [ ] **exportPdf() method** - Fix Penelitian query (line ~203)
- [ ] **exportPdf() method** - Fix Publikasi query (line ~216)
- [ ] **exportPdf() method** - Fix Pengmas query (line ~228)
- [ ] **productivity() method** - Fix Penelitian query (line ~252)
- [ ] **productivity() method** - Fix Publikasi query (line ~258)
- [ ] **productivity() method** - Fix Pengmas query (line ~264)

### 3. Perbaikan PenelitianController.php
- [ ] **index() method** - Fix tahun_akademik filter (line ~46)

### 4. Testing dan Validasi
- [ ] Test export Excel dengan berbagai kombinasi filter
- [ ] Test export PDF dengan berbagai kombinasi filter  
- [ ] Test filter di halaman index
- [ ] Validasi hasil sesuai dengan data di database

### 5. Dokumentasi
- [ ] Update dokumentasi perubahan
- [ ] Test case examples

## Catatan Implementasi

**Pattern yang akan digunakan:**
```php
// Untuk single year
$query->where('tahun_akademik', 'like', $year . '%');

// Untuk multiple years  
$query->where(function ($q) use ($years) {
    foreach ($years as $year) {
        $q->orWhere('tahun_akademik', 'like', $year . '%');
    }
});
