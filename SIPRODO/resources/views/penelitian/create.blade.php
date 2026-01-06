@php
    use Illuminate\Support\Str;
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight" style="color: #003366;">
            {{ isset($penelitian) ? __('Edit Data Penelitian') : __('Tambah Data Penelitian Baru') }}
        </h2>
    </x-slot>

    <div class="min-h-screen py-12" style="background-color: #f8f8f8;">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <form method="POST" action="{{ isset($penelitian) ? route('penelitian.update', $penelitian) : route('penelitian.store') }}" enctype="multipart/form-data">
                @csrf
                @if(isset($penelitian))
                    @method('PUT')
                @endif
                
                {{-- Header Card --}}
                <div class="bg-white rounded-xl shadow-xl p-6 mb-6 border-l-4 border-telkom-green">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-telkom-blue-light rounded-lg flex items-center justify-center">
                            <i class="fas fa-microscope w-6 h-6 text-telkom-blue"></i>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">Formulir Data Penelitian</h1>
                            <p class="text-sm text-gray-600 mt-1">Lengkapi informasi penelitian Anda dengan detail</p>
                        </div>
                    </div>
                </div>

                {{-- Section 1: Informasi Utama --}}
                <div class="bg-white rounded-xl shadow-xl p-6 mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b border-gray-100">Informasi Utama</h2>
                    
                    <div class="space-y-6">
                        
                        <div>
                            <label for="judul_penelitian" class="block text-sm font-medium text-gray-700 mb-2">Judul Penelitian <span class="text-red-600">*</span></label>
                            <input type="text" id="judul_penelitian" name="judul_penelitian" value="{{ old('judul_penelitian', $penelitian->judul_penelitian ?? '') }}" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all"
                                   placeholder="Masukkan judul penelitian">
                            @error('judul_penelitian')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="abstrak" class="block text-sm font-medium text-gray-700 mb-2">Abstrak <span class="text-red-600">*</span></label>
                            <textarea id="abstrak" name="abstrak" rows="4" required
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all resize-none"
                                      placeholder="Jelaskan secara singkat mengenai penelitian ini...">{{ old('abstrak', $penelitian->abstrak ?? '') }}</textarea>
                            @error('abstrak')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="jenis" class="block text-sm font-medium text-gray-700 mb-2">Jenis Penelitian <span class="text-red-600">*</span></label>
                                <select id="jenis" name="jenis" required
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all bg-white">
                                    <option value="">Pilih Jenis</option>
                                    <option value="mandiri" {{ old('jenis', $penelitian->jenis ?? '') == 'mandiri' ? 'selected' : '' }}>Mandiri</option>
                                    <option value="hibah_internal" {{ old('jenis', $penelitian->jenis ?? '') == 'hibah_internal' ? 'selected' : '' }}>Hibah Internal</option>
                                    <option value="hibah_eksternal" {{ old('jenis', $penelitian->jenis ?? '') == 'hibah_eksternal' ? 'selected' : '' }}>Hibah Eksternal</option>
                                    <option value="kerjasama" {{ old('jenis', $penelitian->jenis ?? '') == 'kerjasama' ? 'selected' : '' }}>Kerjasama</option>
                                </select>
                                @error('jenis')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status Penelitian <span class="text-red-600">*</span></label>
                                <select id="status" name="status" required
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all bg-white">
                                    <option value="">Pilih Status</option>
                                    <option value="proposal" {{ old('status', $penelitian->status ?? '') == 'proposal' ? 'selected' : '' }}>📋 Proposal</option>
                                    <option value="berjalan" {{ old('status', $penelitian->status ?? '') == 'berjalan' ? 'selected' : '' }}>🏃 Berjalan</option>
                                    <option value="selesai" {{ old('status', $penelitian->status ?? '') == 'selesai' ? 'selected' : '' }}>✅ Selesai</option>
                                </select>
                                @error('status')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 2: Waktu & Pelaksanaan --}}
                <div class="bg-white rounded-xl shadow-xl p-6 mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b border-gray-100">Waktu & Pelaksanaan</h2>
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="tahun" class="block text-sm font-medium text-gray-700 mb-2">Tahun <span class="text-red-600">*</span></label>
                                <input type="text" id="tahun" name="tahun" value="{{ old('tahun', $penelitian->tahun ?? (date('Y') . '/' . (date('Y') + 1))) }}" required
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all"
                                       placeholder="2024/2025">
                                @error('tahun')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="semester" class="block text-sm font-medium text-gray-700 mb-2">Semester <span class="text-red-600">*</span></label>
                                <select id="semester" name="semester" required
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all bg-white">
                                    <option value="">Pilih Semester</option>
                                    <option value="ganjil" {{ old('semester', $penelitian->semester ?? '') == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                                    <option value="genap" {{ old('semester', $penelitian->semester ?? '') == 'genap' ? 'selected' : '' }}>Genap</option>
                                </select>
                                @error('semester')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai <span class="text-red-600">*</span></label>
                                <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai', $penelitian->tanggal_mulai ?? '') }}" required
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all">
                                @error('tanggal_mulai')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai <span class="text-red-600">*</span></label>
                                <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai', $penelitian->tanggal_selesai ?? '') }}" required
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all">
                                @error('tanggal_selesai')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 3: Pendanaan --}}
                <div class="bg-white rounded-xl shadow-xl p-6 mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b border-gray-100">Pendanaan</h2>
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="dana" class="block text-sm font-medium text-gray-700 mb-2">Nominal Dana (Rp)</label>
                                <input type="number" id="dana" name="dana" value="{{ old('dana', $penelitian->dana ?? '') }}" min="0"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all"
                                       placeholder="0">
                                @error('dana')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="sumber_dana" class="block text-sm font-medium text-gray-700 mb-2">Sumber Dana</label>
                                <input type="text" id="sumber_dana" name="sumber_dana" value="{{ old('sumber_dana', $penelitian->sumber_dana ?? '') }}"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all"
                                       placeholder="Contoh: DIKTI, Internal, dll">
                                @error('sumber_dana')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 4: Dokumen --}}
                <div class="bg-white rounded-xl shadow-xl p-6 mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b border-gray-100">Dokumen</h2>
                    
                    <div class="space-y-6">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            {{-- File Proposal --}}
                            <div>
                                <label for="file_proposal" class="block text-sm font-medium text-gray-700 mb-2">File Proposal (PDF, max 10MB)</label>
                                
                                @if(isset($penelitian) && $penelitian->file_proposal)
                                    {{-- Tampilan jika file sudah ada --}}
                                    <div class="p-4 bg-telkom-blue-light border border-gray-300 rounded-lg file-card" id="file_proposal_card">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <i class="fas fa-file-pdf w-6 h-6 text-red-500 mr-3"></i>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">{{ Str::limit(preg_replace('/^\d+_/', '', basename($penelitian->file_proposal)), 30, '...') }}</p>
                                                    <p class="text-xs text-gray-500">Dokumen tersimpan</p>
                                                </div>
                                            </div>
                                            <a href="{{ Storage::url($penelitian->file_proposal) }}" target="_blank" class="text-telkom-blue hover:underline text-sm">Lihat</a>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Pilih file baru di bawah ini jika ingin mengganti dokumen.</p>
                                    {{-- Tampilan Upload Ganti File (Gaya Dropzone) --}}
                                    <div class="file-upload-area" id="file_proposal_replace">
                                        <label for="file_proposal" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <i class="fas fa-cloud-upload-alt w-8 h-8 text-gray-500 mb-2"></i>
                                                <p class="text-sm text-gray-600">
                                                    <span class="text-gray-500 font-semibold">Klik untuk mengganti file</span> atau drag and drop
                                                </p>
                                                <p class="text-xs text-gray-400 mt-1">PDF (MAX. 10MB)</p>
                                            </div>
                                            <input type="file" id="file_proposal" name="file_proposal" accept=".pdf" class="hidden">
                                        </label>
                                    </div>
                                @else
                                    {{-- Tampilan Upload Kosong (Gaya Dropzone) --}}
                                    <div class="file-upload-area" id="file_proposal_upload">
                                        <label for="file_proposal" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <i class="fas fa-cloud-upload-alt w-8 h-8 text-gray-500 mb-2"></i>
                                                <p class="text-sm text-gray-600">
                                                    <span class="text-gray-500 font-semibold">Klik untuk upload</span> atau drag and drop
                                                </p>
                                                <p class="text-xs text-gray-400 mt-1">PDF (MAX. 10MB)</p>
                                            </div>
                                            <input type="file" id="file_proposal" name="file_proposal" accept=".pdf" class="hidden">
                                        </label>
                                    </div>
                                @endif
                                @error('file_proposal')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            {{-- File Laporan --}}
                            <div>
                                <label for="file_laporan" class="block text-sm font-medium text-gray-700 mb-2">File Laporan (PDF, max 10MB)</label>
                                
                                @if(isset($penelitian) && $penelitian->file_laporan)
                                    {{-- Tampilan jika file sudah ada --}}
                                    <div class="p-4 bg-telkom-blue-light border border-gray-300 rounded-lg file-card" id="file_laporan_card">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <i class="fas fa-file-pdf w-6 h-6 text-red-500 mr-3"></i>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">{{ Str::limit(preg_replace('/^\d+_/', '', basename($penelitian->file_laporan)), 30, '...') }}</p>
                                                    <p class="text-xs text-gray-500">Dokumen tersimpan</p>
                                                </div>
                                            </div>
                                            <a href="{{ Storage::url($penelitian->file_laporan) }}" target="_blank" class="text-telkom-blue hover:underline text-sm">Lihat</a>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Pilih file baru di bawah ini jika ingin mengganti dokumen.</p>
                                    {{-- Tampilan Upload Ganti File (Gaya Dropzone) --}}
                                    <div class="file-upload-area" id="file_laporan_replace">
                                        <label for="file_laporan" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <i class="fas fa-cloud-upload-alt w-8 h-8 text-gray-500 mb-2"></i>
                                                <p class="text-sm text-gray-600">
                                                    <span class="text-gray-500 font-semibold">Klik untuk mengganti file</span> atau drag and drop
                                                </p>
                                                <p class="text-xs text-gray-400 mt-1">PDF (MAX. 10MB)</p>
                                            </div>
                                            <input type="file" id="file_laporan" name="file_laporan" accept=".pdf" class="hidden">
                                        </label>
                                    </div>
                                @else
                                    {{-- Tampilan Upload Kosong (Gaya Dropzone) --}}
                                    <div class="file-upload-area" id="file_laporan_upload">
                                        <label for="file_laporan" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <i class="fas fa-cloud-upload-alt w-8 h-8 text-gray-500 mb-2"></i>
                                                <p class="text-sm text-gray-600">
                                                    <span class="text-gray-500 font-semibold">Klik untuk upload</span> atau drag and drop
                                                </p>
                                                <p class="text-xs text-gray-400 mt-1">PDF (MAX. 10MB)</p>
                                            </div>
                                            <input type="file" id="file_laporan" name="file_laporan" accept=".pdf" class="hidden">
                                        </label>
                                    </div>
                                @endif
                                @error('file_laporan')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Catatan --}}
                        <div>
                            <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                            <textarea id="catatan" name="catatan" rows="4" placeholder="Tambahkan catatan atau informasi tambahan..."
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all resize-none">{{ old('catatan', $penelitian->catatan ?? '') }}</textarea>
                            @error('catatan')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-6 flex justify-end gap-3">
                    <a href="{{ route('penelitian.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors shadow-sm font-medium">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 hover:shadow-lg transform hover:scale-105 active:scale-95 transition-all duration-200 font-semibold">
                        Simpan Penelitian
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Function to handle file input change
        function handleFileChange(inputId, uploadAreaId, cardId) {
            const input = document.getElementById(inputId);
            const uploadArea = document.getElementById(uploadAreaId);
            const existingCard = document.getElementById(cardId);

            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Hide upload area
                    uploadArea.style.display = 'none';

                    // Create or update file card
                    let card = existingCard;
                    if (!card) {
                        card = document.createElement('div');
                        card.id = cardId;
                        card.className = 'p-4 bg-telkom-blue-light border border-gray-300 rounded-lg file-card';
                        uploadArea.parentNode.insertBefore(card, uploadArea);
                    }

                    // Limit filename length
                    const maxLength = 30;
                    const displayName = file.name.length > maxLength ? file.name.substring(0, maxLength) + '...' : file.name;

                    card.innerHTML = `
                        <div class="flex items-center justify-between">
                            <div class="flex items-center flex-1 min-w-0 mr-3">
                                <i class="fas fa-file-pdf w-6 h-6 text-red-500 mr-3 flex-shrink-0"></i>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900 truncate">${displayName}</p>
                                    <p class="text-xs text-gray-500">File dipilih</p>
                                </div>
                            </div>
                            <button type="button" onclick="removeFile('${inputId}', '${uploadAreaId}', '${cardId}')" class="text-red-500 hover:text-red-700 text-sm flex-shrink-0">
                                <i class="fas fa-times"></i> Hapus
                            </button>
                        </div>
                    `;
                }
            });
        }

        // Function to remove selected file
        function removeFile(inputId, uploadAreaId, cardId) {
            const input = document.getElementById(inputId);
            const uploadArea = document.getElementById(uploadAreaId);
            const card = document.getElementById(cardId);

            // Clear input
            input.value = '';

            // Hide card and show upload area
            if (card) {
                card.style.display = 'none';
            }
            uploadArea.style.display = 'block';
        }

        // NEW: Setup drag & drop on upload areas so dropped files set the input and trigger change
        function setupDragAndDrop(inputId, uploadAreaId) {
            const input = document.getElementById(inputId);
            const uploadArea = document.getElementById(uploadAreaId);
            if (!input || !uploadArea) return;

            // target zone to apply highlight classes (prefer the label inside if exists)
            const zone = uploadArea.querySelector('label') || uploadArea;

            // prevent default for drag events
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                zone.addEventListener(eventName, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }, false);
            });

            // add highlight on enter/over
            ['dragenter', 'dragover'].forEach(eventName => {
                zone.addEventListener(eventName, () => zone.classList.add('border-blue-500', 'bg-blue-50'), false);
            });

            // remove highlight on leave/drop
            ['dragleave', 'drop'].forEach(eventName => {
                zone.addEventListener(eventName, () => zone.classList.remove('border-blue-500', 'bg-blue-50'), false);
            });

            // handle drop -> set files and dispatch change so existing handler runs
            zone.addEventListener('drop', (e) => {
                const dt = e.dataTransfer;
                const files = dt.files;
                if (files && files.length) {
                    input.files = files;
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                }
            }, false);
        }

        // Initialize file handlers and drag & drop
        document.addEventListener('DOMContentLoaded', function() {
            handleFileChange('file_proposal', 'file_proposal_upload', 'file_proposal_card');
            handleFileChange('file_proposal', 'file_proposal_replace', 'file_proposal_card');
            handleFileChange('file_laporan', 'file_laporan_upload', 'file_laporan_card');
            handleFileChange('file_laporan', 'file_laporan_replace', 'file_laporan_card');

            // Setup drag & drop on available upload areas (no-op if area not present)
            setupDragAndDrop('file_proposal', 'file_proposal_upload');
            setupDragAndDrop('file_proposal', 'file_proposal_replace');
            setupDragAndDrop('file_laporan', 'file_laporan_upload');
            setupDragAndDrop('file_laporan', 'file_laporan_replace');
        });
    </script>
</x-app-layout>
