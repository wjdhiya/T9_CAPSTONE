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
                                <label class="block text-sm font-medium text-gray-700 mb-2">File Proposal (PDF)</label>
                                @if($pengabdianMasyarakat->file_proposal)
                                    {{-- Tampilan jika file sudah ada --}}
                                    <div class="p-4 bg-telkom-blue-light border border-gray-300 rounded-lg">
                                        <p class="text-sm text-gray-900 font-medium">Dokumen Tersimpan: <a href="{{ Storage::url($pengabdianMasyarakat->file_proposal) }}" target="_blank" class="text-telkom-blue underline">Lihat File</a></p>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Pilih file baru di bawah ini jika ingin mengganti dokumen.</p>
                                    <input type="file" name="file_proposal" accept=".pdf" class="mt-3 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-telkom-blue file:text-white hover:file:bg-blue-800">
                                @else
                                    {{-- Tampilan Upload Kosong --}}
                                    <input type="file" name="file_proposal" accept=".pdf" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-telkom-blue file:text-white hover:file:bg-blue-800">
                                @endif
                                @error('file_proposal')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            {{-- File Laporan --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">File Laporan (PDF)</label>
                                @if($pengabdianMasyarakat->file_laporan)
                                    {{-- Tampilan jika file sudah ada --}}
                                    <div class="p-4 bg-telkom-blue-light border border-gray-300 rounded-lg">
                                        <p class="text-sm text-gray-900 font-medium">Dokumen Tersimpan: <a href="{{ Storage::url($pengabdianMasyarakat->file_laporan) }}" target="_blank" class="text-telkom-blue underline">Lihat File</a></p>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Pilih file baru di bawah ini jika ingin mengganti dokumen.</p>
                                    <input type="file" name="file_laporan" accept=".pdf" class="mt-3 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-telkom-blue file:text-white hover:file:bg-blue-800">
                                @else
                                    {{-- Tampilan Upload Kosong --}}
                                    <input type="file" name="file_laporan" accept=".pdf" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-telkom-blue file:text-white hover:file:bg-blue-800">
                                @endif
                                @error('file_laporan')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            {{-- Dokumentasi --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Dokumentasi (Foto/ZIP)</label>
                                @if($pengabdianMasyarakat->file_dokumentasi)
                                    {{-- Tampilan jika file sudah ada --}}
                                    <div class="p-4 bg-telkom-blue-light border border-gray-300 rounded-lg">
                                        <p class="text-sm text-gray-900 font-medium">Dokumen Tersimpan: <a href="{{ Storage::url($pengabdianMasyarakat->file_dokumentasi) }}" target="_blank" class="text-telkom-blue underline">Lihat File</a></p>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Pilih file baru di bawah ini jika ingin mengganti dokumen.</p>
                                    <input type="file" name="file_dokumentasi" class="mt-3 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-telkom-blue file:text-white hover:file:bg-blue-800">
                                @else
                                    {{-- Tampilan Upload Kosong --}}
                                    <input type="file" name="file_dokumentasi" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-telkom-blue file:text-white hover:file:bg-blue-800">
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
                    <button type="submit" class="px-6 py-2.5 bg-telkom-green text-white rounded-lg hover:bg-telkom-green hover:shadow-lg transform hover:scale-105 active:scale-95 transition-all duration-200 font-semibold">
                        Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
