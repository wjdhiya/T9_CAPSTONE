# Rencana Perbaikan: Export Data Penelitian dan Pengabdian Masyarakat

## Analisis Masalah

### 1. Root Cause
Masalah utama terletak pada cara filtering `tahun_akademik` di ReportController dan controller-controller lainnya:

**Format Data:**
- Database menyimpan: `2024/2025`, `2025/2026` (format string dengan slash)
- User input di form: `2024`, `2025` (hanya tahun pertama)
- Query saat ini: `where('tahun_akademik', 2025)` ❌ TIDAK MATCH
- Yang seharusnya: `where('tahun_akademik', 'like', '2025%')` ✅ AKAN MATCH

### 2. Lokasi Masalah

#### A. ReportController.php
**Masalah pada method:**
- `getStatistics()` (line 47, 67, 89)
- `exportExcel()` (line 129, 153, 175)
- `exportPdf()` (line 203, 216, 228)
- `productivity()` (line 252, 258, 264)

**Contoh kode bermasalah:**
```php
$penelitianQuery = Penelitian::where('tahun_akademik', $tahun_akademik);
```

#### B. PenelitianController.php
**Masalah pada method index():**
- Line 46: `$query->where('tahun_akademik', $request->tahun_akademik);`

#### C. Inkonsistensi Model
**Di Model Penelitian.php sudah benar:**
```php
public function scopeByYear($query, $year)
{
    return $query->where('tahun_akademik', 'like', "%$year%");
}
```

## Solusi yang Akan Diterapkan

### 1. Perbaikan ReportController.php dengan Multiple Years Handling

**Pendekatan yang lebih robust** menggunakan `orWhere` untuk menangani berbagai skenario:

```php
// Solusi yang lebih baik - menangani multiple years sekaligus
$years = [2022, 2023, 2024, 2025]; // atau dynamic berdasarkan input

$penelitianQuery = Penelitian::where(function ($q) use ($years) {
    foreach ($years as $year) {
        $q->orWhere('tahun_akademik', 'like', $year . '%');
    }
});
```

**Keunggulan pendekatan ini:**
- Menangani format tahun_akademik seperti "2024/2025" dan "2025/2026"
- Bisa menangani multiple years dalam satu query
- Lebih fleksibel untuk berbagai skenario filter
- Menghindari overlap issues

### 2. Perbaikan PenelitianController.php
- Ubah query filter dari exact match menjadi like pattern
- Atau gunakan scope `byYear()` dari model

### 3. Peningkatan User Experience
- Update dropdown filter untuk menampilkan format lengkap (2024/2025)
- Update placeholder dan label untuk kejelasan format

### 4. Testing
- Test export dengan berbagai kombinasi filter
- Pastikan semua jenis export (Excel, PDF) berfungsi
- Validasi hasil export sesuai dengan filter yang dipilih

## File yang Akan Dimodifikasi

1. **SIPRODO/app/Http/Controllers/ReportController.php** - Perbaikan utama
2. **SIPRODO/app/Http/Controllers/PenelitianController.php** - Perbaikan filter
3. **SIPRODO/resources/views/penelitian/index.blade.php** - Update dropdown filter
4. **SIPRODO/resources/views/pengmas/index.blade.php** - Update dropdown filter
5. **SIPRODO/resources/views/reports/index.blade.php** - Update filter jika ada

## Langkah Implementasi

1. Backup file-file yang akan dimodifikasi
2. Implementasi perbaikan pada ReportController
3. Implementasi perbaikan pada PenelitianController  
4. Update view untuk konsistensi format
5. Test fungsionalitas export
6. Dokumentasi perubahan
