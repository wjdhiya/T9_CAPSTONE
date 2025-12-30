# TODO Plan: Update Style "Choose File" ke Drag & Drop Style

## Task Analysis
User ingin mengubah style "choose file" di bagian Pengmas agar sama dengan drag & drop style yang sudah ada di bagian Penelitian & Publikasi.

## Information Gathered
Dari analisis file yang sudah dilakukan:

### Current Style (Pengmas - Wrong)
- Menggunakan button "choose file" biasa dengan styling:
  `file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-telkom-blue file:text-white hover:file:bg-blue-800`

### Target Style (Penelitian & Publikasi - Correct)
- Menggunakan drag & drop style dengan classes:
  `flex flex-col items-center justify-center w-full h-32 border-2 border-telkom-blue border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-blue-50 transition-colors`
- Dengan icon cloud upload dan teks: "Klik untuk upload atau drag and drop"

## Plan

### Files to be Edited:
1. `SIPRODO/resources/views/pengmas/create.blade.php`
2. `SIPRODO/resources/views/pengmas/edit.blade.php`

### Changes Required:
1. **Update file upload areas** di pengmas/create.blade.php:
   - Replace file input button dengan drag & drop zone
   - Add wrapper div dengan class `file-upload-area` dan id yang sesuai
   - Add label dengan styling drag & drop yang sama
   - Add cloud upload icon dan teks "Klik untuk upload atau drag and drop"
   - Keep input type="file" dengan class "hidden"

2. **Update file upload areas** di pengmas/edit.blade.php:
   - Same changes as create.blade.php
   - Handle conditional logic untuk menampilkan file yang sudah ada vs upload area

3. **Update JavaScript** di kedua file:
   - Adjust function parameters untuk match dengan drag & drop style
   - Update card creation dan file handling logic

### Technical Details:
- **File Proposal**: Change from button style to drag & drop zone
- **File Laporan**: Change from button style to drag & drop zone  
- **File Dokumentasi**: Change from button style to drag & drop zone
- Keep same JavaScript functionality but adjust for new structure
- Maintain consistent styling with telkom-blue colors

## Dependent Files
- SIPRODO/resources/views/pengmas/create.blade.php
- SIPRODO/resources/views/pengmas/edit.blade.php

## Follow-up Steps
After implementing the changes:
1. Test the drag & drop functionality
2. Verify styling consistency with penelitian & publikasi
3. Check that file upload still works properly
4. Ensure responsive design maintained

## Status: ✅ COMPLETED

### Implementation Progress:
- [x] **Update File: `SIPRODO/resources/views/pengmas/create.blade.php`** ✅
  - Replace semua file input button dengan drag & drop zone
  - Add file upload areas dengan styling yang konsisten
  - Add JavaScript untuk handling file change
  - Implement file card display untuk file yang dipilih

- [x] **Update File: `SIPRODO/resources/views/pengmas/edit.blade.php`** ✅
  - Replace semua file input button dengan drag & drop zone
  - Add file upload areas dengan styling yang konsisten
  - Add JavaScript untuk handling file change
  - Implement conditional logic untuk file yang sudah ada vs upload area

### Technical Changes Applied:
1. **File Upload Areas**: Semua menggunakan drag & drop style yang sama dengan penelitian & publikasi
2. **Consistent Styling**: Border dashed biru (`border-telkom-blue border-dashed`)
3. **Visual Elements**: Icon cloud upload, hover effects, responsive design
4. **JavaScript**: File handling, card creation, remove functionality
5. **File Types Support**: PDF files untuk proposal & laporan, image/ZIP untuk dokumentasi

### Files Updated:
- ✅ `SIPRODO/resources/views/pengmas/create.blade.php`
- ✅ `SIPRODO/resources/views/pengmas/edit.blade.php`

**Started**: 2025-01-28
**Completed**: 2025-01-28

## Result: ✅ SUCCESS - Semua style "choose file" di pengmas telah diubah menjadi drag & drop style yang sama dengan penelitian & publikasi!
