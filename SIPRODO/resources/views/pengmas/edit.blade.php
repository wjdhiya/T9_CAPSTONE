<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight" style="color: #003366;">
            {{ __('Edit Data Pengabdian Masyarakat') }}
        </h2>
    </x-slot>

    <div class="min-h-screen py-12" style="background-color: #f8f8f8;">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <form method="POST" action="{{ route('pengmas.update', $pengabdianMasyarakat) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                {{-- Header Card --}}
                <div class="bg-white rounded-xl shadow-xl p-6 mb-6 border-l-4 border-telkom-green">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-telkom-blue-light rounded-lg flex items-center justify-center">
                            <i class="fas fa-hand-holding-heart w-6 h-6 text-telkom-blue"></i>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">Formulir Edit Data Pengabdian Masyarakat</h1>
                            <p class="text-sm text-gray-600 mt-1">Ubah informasi pengadian masyarakat Anda dengan detail</p>
                        </div>
                    </div>
                </div>

                {{-- Section 1: Informasi Utama --}}
                <div class="bg-white rounded-xl shadow-xl p-6 mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b border-gray-100">Informasi Utama</h2>
                    
                    <div class="space-y-6">
                        
                        <div>
                            <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">Judul Kegiatan <span class="text-red-600">*</span></label>
                            <input type="text" id="judul" name="judul" value="{{ old('judul', $pengabdianMasyarakat->judul) }}" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all"
                                   placeholder="Masukkan judul kegiatan pengadian">
                            @error('judul')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="abstrak" class="block text-sm font-medium text-gray-700 mb-2">Abstrak / Deskripsi <span class="text-red-600">*</span></label>
                            <textarea id="abstrak" name="abstrak" rows="4" required
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all resize-none"
                                      placeholder="Jelaskan secara singkat mengenai kegiatan ini...">{{ old('abstrak', $pengabdianMasyarakat->abstrak) }}</textarea>
                            @error('abstrak')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-2">Lokasi Kegiatan <span class="text-red-600">*</span></label>
                                <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi', $pengabdianMasyarakat->lokasi) }}" required
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all"
                                       placeholder="Nama Desa/Kecamatan/Kota">
                                @error('lokasi')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="mitra" class="block text-sm font-medium text-gray-700 mb-2">Mitra Sasaran <span class="text-red-600">*</span></label>
                                <input type="text" id="mitra" name="mitra" value="{{ old('mitra', $pengabdianMasyarakat->mitra) }}" required
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all"
                                       placeholder="Contoh: UMKM Keripik, Kelompok Tani">
                                @error('mitra')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 2: Waktu & Pelaksanaan --}}
                <div class="bg-white rounded-xl shadow-xl p-6 mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b border-gray-100">Waktu & Pelaksanaan</h2>
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div>
                                <label for="tahun_akademik" class="block text-sm font-medium text-gray-700 mb-2">Tahun Akademik <span class="text-red-600">*</span></label>
                                <input type="text" id="tahun_akademik" name="tahun_akademik" value="{{ old('tahun_akademik', $pengabdianMasyarakat->tahun_akademik) }}" required
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all"
                                       placeholder="Cth: 2024/2025">
                                @error('tahun_akademik')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="semester" class="block text-sm font-medium text-gray-700 mb-2">Semester <span class="text-red-600">*</span></label>
                                <select id="semester" name="semester" required
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all bg-white">
                                    <option value="">Pilih Semester</option>
                                    <option value="ganjil" {{ old('semester', $pengabdianMasyarakat->semester) == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                                    <option value="genap" {{ old('semester', $pengabdianMasyarakat->semester) == 'genap' ? 'selected' : '' }}>Genap</option>
                                </select>
                                @error('semester')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="jumlah_peserta" class="block text-sm font-medium text-gray-700 mb-2">Jml. Peserta <span class="text-red-600">*</span></label>
                                <input type="number" id="jumlah_peserta" name="jumlah_peserta" value="{{ old('jumlah_peserta', $pengabdianMasyarakat->jumlah_peserta) }}" min="1" required
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all">
                                @error('jumlah_peserta')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai <span class="text-red-600">*</span></label>
                                <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai', $pengabdianMasyarakat->tanggal_mulai?->format('Y-m-d')) }}" required
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all">
                                @error('tanggal_mulai')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai <span class="text-red-600">*</span></label>
                                <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai', $pengabdianMasyarakat->tanggal_selesai?->format('Y-m-d')) }}" required
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all">
                                @error('tanggal_selesai')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 3: Pendanaan & Tim --}}
                <div class="bg-white rounded-xl shadow-xl p-6 mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b border-gray-100">Pendanaan & Tim</h2>
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="dana" class="block text-sm font-medium text-gray-700 mb-2">Nominal Dana (Rp)</label>
                                <input type="number" id="dana" name="dana" value="{{ old('dana', $pengabdianMasyarakat->dana) }}"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all"
                                       placeholder="0">
                                @error('dana')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="sumber_dana" class="block text-sm font-medium text-gray-700 mb-2">Sumber Dana</label>
                                <input type="text" id="sumber_dana" name="sumber_dana" value="{{ old('sumber_dana', $pengabdianMasyarakat->sumber_dana) }}"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all"
                                       placeholder="Mandiri / Hibah / Kampus">
                                @error('sumber_dana')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Input Dinamis untuk Anggota Dosen --}}
                        <div x-data="{ 
                            items: {{ json_encode(old('anggota', isset($pengabdianMasyarakat) && $pengabdianMasyarakat->anggota ? json_decode($pengabdianMasyarakat->anggota) : [''])) }},
                            addItem() { this.items.push(''); },
                            removeItem(index) { if(this.items.length > 1) this.items.splice(index, 1); }
                        }">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Anggota Dosen (Opsional)</label>
                            <div class="space-y-2">
                                <template x-for="(item, index) in items" :key="index">
                                    <div class="flex items-center gap-2">
                                        <input type="text" :name="'anggota[]'" x-model="items[index]"
                                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all"
                                               placeholder="Nama Dosen Anggota">
                                        <button type="button" @click="removeItem(index)" class="p-2.5 text-red-500 hover:bg-red-50 rounded-lg transition-colors" :disabled="items.length === 1">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </template>
                            </div>
                            <button type="button" @click="addItem()" class="flex items-center gap-2 px-4 py-2 mt-2 text-sm text-telkom-blue bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors border border-transparent">
                                <i class="fas fa-plus"></i> Tambah Anggota
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Section 4: Dokumen & Status --}}
                <div class="bg-white rounded-xl shadow-xl p-6 mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b border-gray-100">Dokumen & Status</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status Kegiatan <span class="text-red-600">*</span></label>
                            <select id="status" name="status" required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-telkom-blue focus:border-transparent transition-all bg-white">
                                <option value="proposal" {{ old('status', $pengabdianMasyarakat->status) == 'proposal' ? 'selected' : '' }}>📋 Proposal (Baru Diajukan)</option>
                                <option value="berjalan" {{ old('status', $pengabdianMasyarakat->status) == 'berjalan' ? 'selected' : '' }}>🏃 Sedang Berjalan</option>
                                <option value="selesai" {{ old('status', $pengabdianMasyarakat->status) == 'selesai' ? 'selected' : '' }}>✅ Selesai</option>
                                <option value="ditolak" {{ old('status', $pengabdianMasyarakat->status) == 'ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
                            </select>
                            @error('status')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            {{-- File Proposal --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">File Proposal (PDF, max 10MB)</label>
                                
                                @if(isset($pengabdianMasyarakat) && $pengabdianMasyarakat->file_proposal)
                                    {{-- Tampilan jika file sudah ada --}}
                                    <div class="p-4 bg-telkom-blue-light border border-gray-300 rounded-lg file-card" id="file_proposal_card">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <i class="fas fa-file-pdf w-6 h-6 text-red-500 mr-3"></i>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">{{ Str::limit(preg_replace('/^\d+_/', '', basename($pengabdianMasyarakat->file_proposal)), 30, '...') }}</p>
                                                    <p class="text-xs text-gray-500">Dokumen tersimpan</p>
                                                </div>
                                            </div>
                                            <a href="{{ Storage::url($pengabdianMasyarakat->file_proposal) }}" target="_blank" class="text-telkom-blue hover:underline text-sm">Lihat</a>
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
                                                <i class="fas fa-cloud-upload-alt w-8 h-8 text-telkom-blue mb-2"></i>
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
                                <label class="block text-sm font-medium text-gray-700 mb-2">File Laporan (PDF, max 10MB)</label>
                                
                                @if(isset($pengabdianMasyarakat) && $pengabdianMasyarakat->file_laporan)
                                    {{-- Tampilan jika file sudah ada --}}
                                    <div class="p-4 bg-telkom-blue-light border border-gray-300 rounded-lg file-card" id="file_laporan_card">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <i class="fas fa-file-pdf w-6 h-6 text-red-500 mr-3"></i>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">{{ Str::limit(preg_replace('/^\d+_/', '', basename($pengabdianMasyarakat->file_laporan)), 30, '...') }}</p>
                                                    <p class="text-xs text-gray-500">Dokumen tersimpan</p>
                                                </div>
                                            </div>
                                            <a href="{{ Storage::url($pengabdianMasyarakat->file_laporan) }}" target="_blank" class="text-telkom-blue hover:underline text-sm">Lihat</a>
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
                                                <i class="fas fa-cloud-upload-alt w-8 h-8 text-telkom-blue mb-2"></i>
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

                            {{-- Dokumentasi --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Dokumentasi (Foto/ZIP, max 10MB)</label>
                                
                                @if(isset($pengabdianMasyarakat) && $pengabdianMasyarakat->file_dokumentasi)
                                    {{-- Tampilan jika file sudah ada --}}
                                    <div class="p-4 bg-telkom-blue-light border border-gray-300 rounded-lg file-card" id="file_dokumentasi_card">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <i class="fas fa-file-archive w-6 h-6 text-yellow-500 mr-3"></i>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">{{ Str::limit(preg_replace('/^\d+_/', '', basename($pengabdianMasyarakat->file_dokumentasi)), 30, '...') }}</p>
                                                    <p class="text-xs text-gray-500">Dokumen tersimpan</p>
                                                </div>
                                            </div>
                                            <a href="{{ Storage::url($pengabdianMasyarakat->file_dokumentasi) }}" target="_blank" class="text-telkom-blue hover:underline text-sm">Lihat</a>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Pilih file baru di bawah ini jika ingin mengganti dokumen.</p>
                                    {{-- Tampilan Upload Ganti File (Gaya Dropzone) --}}
                                    <div class="file-upload-area" id="file_dokumentasi_replace">
                                        <label for="file_dokumentasi" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <i class="fas fa-cloud-upload-alt w-8 h-8 text-telkom-blue mb-2"></i>
                                                <p class="text-sm text-gray-600">
                                                    <span class="text-gray-500 font-semibold">Klik untuk mengganti file</span> atau drag and drop
                                                </p>
                                                <p class="text-xs text-gray-400 mt-1">JPG/PNG/ZIP (MAX. 10MB)</p>
                                            </div>
                                            <input type="file" id="file_dokumentasi" name="file_dokumentasi" class="hidden">
                                        </label>
                                    </div>
                                @else
                                    {{-- Tampilan Upload Kosong (Gaya Dropzone) --}}
                                    <div class="file-upload-area" id="file_dokumentasi_upload">
                                        <label for="file_dokumentasi" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <i class="fas fa-cloud-upload-alt w-8 h-8 text-telkom-blue mb-2"></i>
                                                <p class="text-sm text-gray-600">
                                                    <span class="text-gray-500 font-semibold">Klik untuk upload</span> atau drag and drop
                                                </p>
                                                <p class="text-xs text-gray-400 mt-1">JPG/PNG/ZIP (MAX. 10MB)</p>
                                            </div>
                                            <input type="file" id="file_dokumentasi" name="file_dokumentasi" class="hidden">
                                        </label>
                                    </div>
                                @endif
                                @error('file_dokumentasi')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-6 flex justify-end gap-3">
                    <a href="{{ route('pengmas.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors shadow-sm font-medium">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 hover:shadow-lg transform hover:scale-105 active:scale-95 transition-all duration-200 font-semibold">
                        Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Function to limit string length
        function limitString(str, maxLength = 30) {
            if (str.length <= maxLength) return str;
            return str.substring(0, maxLength - 3) + '...';
        }

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
                        card.className = 'p-4 bg-telkom-blue-light border border-gray-300 rounded-lg file-card mt-2';
                        uploadArea.parentNode.insertBefore(card, uploadArea.nextSibling);
                    }

                    // Determine icon based on file type
                    let iconClass = 'fas fa-file-pdf w-6 h-6 text-red-500';
                    let fileType = 'File dipilih';
                    
                    if (file.type.startsWith('image/')) {
                        iconClass = 'fas fa-file-image w-6 h-6 text-blue-500';
                        fileType = 'Gambar dipilih';
                    } else if (file.name.endsWith('.zip')) {
                        iconClass = 'fas fa-file-archive w-6 h-6 text-yellow-500';
                        fileType = 'Arsip ZIP dipilih';
                    }

                    card.innerHTML = `
                        <div class="flex items-center justify-between">
                            <div class="flex items-center flex-1 min-w-0 mr-3">
                                <i class="${iconClass} mr-3 flex-shrink-0"></i>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900 truncate">${limitString(file.name)}</p>
                                    <p class="text-xs text-gray-500">${fileType}</p>
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
            if (uploadArea) {
                uploadArea.style.display = 'block';
            }
        }

        // Initialize file handlers
        document.addEventListener('DOMContentLoaded', function() {
            handleFileChange('file_proposal', 'file_proposal_upload', 'file_proposal_card');
            handleFileChange('file_proposal', 'file_proposal_replace', 'file_proposal_card');
            handleFileChange('file_laporan', 'file_laporan_upload', 'file_laporan_card');
            handleFileChange('file_laporan', 'file_laporan_replace', 'file_laporan_card');
            handleFileChange('file_dokumentasi', 'file_dokumentasi_upload', 'file_dokumentasi_card');
            handleFileChange('file_dokumentasi', 'file_dokumentasi_replace', 'file_dokumentasi_card');
        });
    </script>
</x-app-layout>
